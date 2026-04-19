<?php

namespace App\Services;

use App\Models\Mountain;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SplPriorityQueue;
use Throwable;

class TrailDataService
{
    /**
     * @param  array<int, array{lat: float|int, lng: float|int}>  $fallbackPath
     * @return array{label: string, path: array<int, array{lat: float, lng: float}>, source: string, sourceLabel: string}
     */
    public function buildTrailMap(Mountain $mountain, string $label, array $fallbackPath = []): array
    {
        $normalizedFallback = $this->normalizePath($fallbackPath);

        if (count($normalizedFallback) < 2) {
            $normalizedFallback = [
                ['lat' => (float) $mountain->jumpoff_lat, 'lng' => (float) $mountain->jumpoff_lng],
                ['lat' => (float) $mountain->summit_lat, 'lng' => (float) $mountain->summit_lng],
            ];
        }

        if (! $this->trailDataEnabled()) {
            return $this->fallbackMap($label, $normalizedFallback);
        }

        $cacheKey = sprintf(
            'trail-map:%s:v3:%s:%s:%s:%s:%s',
            $mountain->slug,
            number_format((float) $mountain->jumpoff_lat, 4, '.', ''),
            number_format((float) $mountain->jumpoff_lng, 4, '.', ''),
            number_format((float) $mountain->summit_lat, 4, '.', ''),
            number_format((float) $mountain->summit_lng, 4, '.', ''),
            substr(md5(json_encode($normalizedFallback)), 0, 12)
        );

        $resolver = fn () => $this->resolveTrailMap($mountain, $label, $normalizedFallback);

        try {
            $cached = Cache::get($cacheKey);

            if (is_array($cached)) {
                return $cached;
            }

            $resolved = $resolver();

            Cache::put(
                $cacheKey,
                $resolved,
                $resolved['source'] === 'fallback'
                    ? now()->addMinutes(30)
                    : now()->addHours($this->trailCacheHours())
            );

            return $resolved;
        } catch (Throwable $e) {
            Log::warning('Trail cache unavailable, resolving without cache.', [
                'mountain' => $mountain->slug,
                'message' => $e->getMessage(),
            ]);

            return $resolver();
        }
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $fallbackPath
     * @return array{label: string, path: array<int, array{lat: float, lng: float}>, source: string, sourceLabel: string}
     */
    private function resolveTrailMap(Mountain $mountain, string $label, array $fallbackPath): array
    {
        try {
            $trailMap = $this->fetchTrailMap($mountain);
            $alignedPath = $this->alignPathToEndpoints($trailMap['path'] ?? [], $mountain);

            if (count($alignedPath) > 1) {
                return [
                    'label' => $label,
                    'path' => $alignedPath,
                    'source' => 'openstreetmap',
                    'sourceLabel' => $trailMap['sourceLabel'] ?? 'Live trail data from OpenStreetMap',
                ];
            }
        } catch (Throwable $e) {
            Log::warning('Trail data fetch failed.', [
                'mountain' => $mountain->slug,
                'message' => $e->getMessage(),
            ]);
        }

        return $this->fallbackMap($label, $fallbackPath);
    }

    /**
     * @return array{path: array<int, array{lat: float, lng: float}>, sourceLabel: string}
     */
    private function fetchTrailMap(Mountain $mountain): array
    {
        $relationElements = $this->requestHikingRouteElements($mountain);
        $relationPath = $this->bestRelationPath($relationElements, $mountain);

        if (count($relationPath) > 1) {
            return [
                'path' => $relationPath,
                'sourceLabel' => 'Live hiking route from OpenStreetMap',
            ];
        }

        $trailPath = $this->fetchTrailPath($mountain);

        return [
            'path' => $trailPath,
            'sourceLabel' => 'Live trail geometry from OpenStreetMap',
        ];
    }

    /**
     * @return array<int, array{lat: float, lng: float}>
     */
    private function fetchTrailPath(Mountain $mountain): array
    {
        $elements = $this->requestTrailWays($mountain);
        [$graph, $nodeCoords] = $this->buildGraph($elements);

        if ($graph === [] || $nodeCoords === []) {
            return [];
        }

        $startCandidates = $this->nearestNodeCandidates($nodeCoords, [
            'lat' => (float) $mountain->jumpoff_lat,
            'lng' => (float) $mountain->jumpoff_lng,
        ]);
        $endCandidates = $this->nearestNodeCandidates($nodeCoords, [
            'lat' => (float) $mountain->summit_lat,
            'lng' => (float) $mountain->summit_lng,
        ]);

        if ($startCandidates === [] || $endCandidates === []) {
            return [];
        }

        $primaryStart = array_key_first($startCandidates);
        $primaryEnd = array_key_first($endCandidates);

        if (is_string($primaryStart) && is_string($primaryEnd)) {
            $primaryPath = $this->shortestPath($graph, $nodeCoords, $primaryStart, $primaryEnd);

            if (count($primaryPath) > 1) {
                return $primaryPath;
            }
        }

        $bestPath = [];
        $bestScore = INF;

        foreach ($startCandidates as $startId => $startOffset) {
            foreach ($endCandidates as $endId => $endOffset) {
                $path = $this->shortestPath($graph, $nodeCoords, $startId, $endId);

                if (count($path) < 2) {
                    continue;
                }

                $score = $this->polylineLengthKm($path) + $startOffset + $endOffset;

                if ($score < $bestScore) {
                    $bestScore = $score;
                    $bestPath = $path;
                }
            }
        }

        return $bestPath;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function requestHikingRouteElements(Mountain $mountain): array
    {
        $bbox = $this->boundingBox($mountain, 0.02);
        $query = <<<OVERPASS
[out:json][timeout:25];
(
  relation["route"="hiking"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});
);
out body;
>;
out skel geom;
OVERPASS;

        $response = Http::acceptJson()
            ->timeout(18)
            ->retry(2, 400, throw: false)
            ->get(config('services.trail_data.overpass_url'), [
                'data' => $query,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Overpass hiking relation request failed with status '.$response->status());
        }

        $payload = $response->json();

        return is_array($payload['elements'] ?? null) ? $payload['elements'] : [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $elements
     * @return array<int, array{lat: float, lng: float}>
     */
    private function bestRelationPath(array $elements, Mountain $mountain): array
    {
        $wayGeometryById = [];
        $relations = [];

        foreach ($elements as $element) {
            if (($element['type'] ?? null) === 'way' && is_array($element['geometry'] ?? null)) {
                $wayGeometryById[(int) $element['id']] = $this->normalizeGeometry($element['geometry']);
            }

            if (($element['type'] ?? null) === 'relation' && ($element['tags']['route'] ?? null) === 'hiking') {
                $relations[] = $element;
            }
        }

        $bestPath = [];
        $bestScore = INF;

        foreach ($relations as $relation) {
            $path = $this->buildRelationPath($relation, $wayGeometryById);

            if (count($path) < 2) {
                continue;
            }

            $score = $this->endpointMatchScore($path, $mountain);

            if ($score > $this->relationEndpointMatchThresholdKm()) {
                continue;
            }

            if ($score < $bestScore) {
                $bestScore = $score;
                $bestPath = $this->orientPathForMountain($path, $mountain);
            }
        }

        return $bestPath;
    }

    /**
     * @param  array<string, mixed>  $relation
     * @param  array<int, array<int, array{lat: float, lng: float}>>  $wayGeometryById
     * @return array<int, array{lat: float, lng: float}>
     */
    private function buildRelationPath(array $relation, array $wayGeometryById): array
    {
        $path = [];

        foreach ($relation['members'] ?? [] as $member) {
            if (($member['type'] ?? null) !== 'way') {
                continue;
            }

            $segment = $wayGeometryById[(int) ($member['ref'] ?? 0)] ?? [];

            if (count($segment) < 2) {
                continue;
            }

            if ($path === []) {
                $path = $segment;
                continue;
            }

            $lastPoint = $path[count($path) - 1];
            $startDistance = $this->distanceKm($lastPoint, $segment[0]);
            $endDistance = $this->distanceKm($lastPoint, $segment[count($segment) - 1]);

            if ($endDistance < $startDistance) {
                $segment = array_reverse($segment);
            }

            if ($this->distanceKm($path[count($path) - 1], $segment[0]) < 0.015) {
                array_shift($segment);
            }

            $path = [...$path, ...$segment];
        }

        return $this->dedupePath($path);
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $path
     */
    private function endpointMatchScore(array $path, Mountain $mountain): float
    {
        $jumpoff = [
            'lat' => (float) $mountain->jumpoff_lat,
            'lng' => (float) $mountain->jumpoff_lng,
        ];
        $summit = [
            'lat' => (float) $mountain->summit_lat,
            'lng' => (float) $mountain->summit_lng,
        ];
        $first = $path[0];
        $last = $path[count($path) - 1];

        $forward = $this->distanceKm($jumpoff, $first) + $this->distanceKm($summit, $last);
        $reverse = $this->distanceKm($jumpoff, $last) + $this->distanceKm($summit, $first);

        return min($forward, $reverse);
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $path
     * @return array<int, array{lat: float, lng: float}>
     */
    private function orientPathForMountain(array $path, Mountain $mountain): array
    {
        $jumpoff = [
            'lat' => (float) $mountain->jumpoff_lat,
            'lng' => (float) $mountain->jumpoff_lng,
        ];
        $summit = [
            'lat' => (float) $mountain->summit_lat,
            'lng' => (float) $mountain->summit_lng,
        ];
        $first = $path[0];
        $last = $path[count($path) - 1];
        $forward = $this->distanceKm($jumpoff, $first) + $this->distanceKm($summit, $last);
        $reverse = $this->distanceKm($jumpoff, $last) + $this->distanceKm($summit, $first);

        return $reverse < $forward ? array_reverse($path) : $path;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function requestTrailWays(Mountain $mountain): array
    {
        $bbox = $this->boundingBox($mountain, 0.0125);
        $query = <<<OVERPASS
[out:json][timeout:25];
(
  way["highway"~"^(path|footway|track|steps|service)$"]["access"!~"^private$"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});
);
out geom;
OVERPASS;

        $response = Http::acceptJson()
            ->timeout(18)
            ->retry(2, 400, throw: false)
            ->get(config('services.trail_data.overpass_url'), [
                'data' => $query,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Overpass request failed with status '.$response->status());
        }

        $payload = $response->json();

        return is_array($payload['elements'] ?? null) ? $payload['elements'] : [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $elements
     * @return array{0: array<string, array<int, array{to: string, distance: float}>>, 1: array<string, array{lat: float, lng: float}>}
     */
    private function buildGraph(array $elements): array
    {
        $graph = [];
        $nodeCoords = [];

        foreach ($elements as $element) {
            if (($element['type'] ?? null) !== 'way') {
                continue;
            }

            $nodeIds = $element['nodes'] ?? [];
            $geometry = $element['geometry'] ?? [];
            $pointCount = min(count($nodeIds), count($geometry));

            if ($pointCount < 2) {
                continue;
            }

            for ($index = 0; $index < $pointCount; $index++) {
                $nodeKey = 'n'.$nodeIds[$index];
                $nodeCoords[$nodeKey] = [
                    'lat' => (float) ($geometry[$index]['lat'] ?? 0.0),
                    'lng' => (float) ($geometry[$index]['lon'] ?? 0.0),
                ];
            }

            for ($index = 0; $index < $pointCount - 1; $index++) {
                $from = 'n'.$nodeIds[$index];
                $to = 'n'.$nodeIds[$index + 1];
                $fromPoint = $nodeCoords[$from];
                $toPoint = $nodeCoords[$to];
                $distance = $this->distanceKm($fromPoint, $toPoint);

                if ($distance <= 0) {
                    continue;
                }

                $graph[$from][] = ['to' => $to, 'distance' => $distance];
                $graph[$to][] = ['to' => $from, 'distance' => $distance];
            }
        }

        return [$graph, $nodeCoords];
    }

    /**
     * @param  array<string, array{lat: float, lng: float}>  $nodeCoords
     * @param  array{lat: float, lng: float}  $target
     * @return array<string, float>
     */
    private function nearestNodeCandidates(array $nodeCoords, array $target, int $limit = 6): array
    {
        $distances = [];

        foreach ($nodeCoords as $nodeId => $point) {
            $distances[$nodeId] = $this->distanceKm($target, $point);
        }

        asort($distances);

        return array_slice($distances, 0, $limit, true);
    }

    /**
     * @param  array<string, array<int, array{to: string, distance: float}>>  $graph
     * @param  array<string, array{lat: float, lng: float}>  $nodeCoords
     * @return array<int, array{lat: float, lng: float}>
     */
    private function shortestPath(array $graph, array $nodeCoords, string $startId, string $endId): array
    {
        if (! isset($graph[$startId], $graph[$endId], $nodeCoords[$startId], $nodeCoords[$endId])) {
            return [];
        }

        if ($startId === $endId) {
            return [$nodeCoords[$startId]];
        }

        $distances = [$startId => 0.0];
        $previous = [];
        $visited = [];

        $queue = new SplPriorityQueue();
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $queue->insert($startId, 0.0);

        while (! $queue->isEmpty()) {
            $current = $queue->extract();
            $nodeId = $current['data'];
            $distance = -1 * (float) $current['priority'];

            if (isset($visited[$nodeId])) {
                continue;
            }

            $visited[$nodeId] = true;

            if ($nodeId === $endId) {
                break;
            }

            foreach ($graph[$nodeId] ?? [] as $edge) {
                $candidateDistance = $distance + $edge['distance'];

                if ($candidateDistance >= ($distances[$edge['to']] ?? INF)) {
                    continue;
                }

                $distances[$edge['to']] = $candidateDistance;
                $previous[$edge['to']] = $nodeId;
                $queue->insert($edge['to'], -1 * $candidateDistance);
            }
        }

        if (! isset($distances[$endId])) {
            return [];
        }

        $pathNodeIds = [$endId];
        $cursor = $endId;

        while (isset($previous[$cursor])) {
            $cursor = $previous[$cursor];
            array_unshift($pathNodeIds, $cursor);
        }

        return array_values(array_filter(
            array_map(fn (string $nodeId) => $nodeCoords[$nodeId] ?? null, $pathNodeIds)
        ));
    }

    /**
     * @param  array<int, array{lat: float|int, lng: float|int}>  $path
     * @return array<int, array{lat: float, lng: float}>
     */
    private function normalizePath(array $path): array
    {
        return array_values(array_map(
            fn (array $point) => [
                'lat' => (float) ($point['lat'] ?? 0.0),
                'lng' => (float) ($point['lng'] ?? 0.0),
            ],
            $path
        ));
    }

    /**
     * @param  array<int, array{lat: float|int, lon: float|int}>  $geometry
     * @return array<int, array{lat: float, lng: float}>
     */
    private function normalizeGeometry(array $geometry): array
    {
        return array_values(array_map(
            fn (array $point) => [
                'lat' => (float) ($point['lat'] ?? 0.0),
                'lng' => (float) ($point['lon'] ?? 0.0),
            ],
            $geometry
        ));
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $path
     * @return array<int, array{lat: float, lng: float}>
     */
    private function dedupePath(array $path): array
    {
        $deduped = [];
        $lastKey = null;

        foreach ($path as $point) {
            $key = number_format($point['lat'], 6, '.', '').':'.number_format($point['lng'], 6, '.', '');

            if ($key === $lastKey) {
                continue;
            }

            $deduped[] = $point;
            $lastKey = $key;
        }

        return $deduped;
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $path
     * @return array<int, array{lat: float, lng: float}>
     */
    private function alignPathToEndpoints(array $path, Mountain $mountain): array
    {
        if (count($path) < 2) {
            return $path;
        }

        $jumpoff = [
            'lat' => (float) $mountain->jumpoff_lat,
            'lng' => (float) $mountain->jumpoff_lng,
        ];
        $summit = [
            'lat' => (float) $mountain->summit_lat,
            'lng' => (float) $mountain->summit_lng,
        ];
        $aligned = $this->orientPathForMountain($path, $mountain);
        $joinThresholdKm = min(0.75, $this->relationEndpointMatchThresholdKm());
        $snapThresholdKm = 0.025;

        if ($this->distanceKm($jumpoff, $aligned[0]) > $snapThresholdKm && $this->distanceKm($jumpoff, $aligned[0]) <= $joinThresholdKm) {
            array_unshift($aligned, $jumpoff);
        }

        if ($this->distanceKm($summit, $aligned[count($aligned) - 1]) > $snapThresholdKm && $this->distanceKm($summit, $aligned[count($aligned) - 1]) <= $joinThresholdKm) {
            $aligned[] = $summit;
        }

        return $this->dedupePath($aligned);
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $path
     * @return array{label: string, path: array<int, array{lat: float, lng: float}>, source: string, sourceLabel: string}
     */
    private function fallbackMap(string $label, array $path): array
    {
        return [
            'label' => $label,
            'path' => $path,
            'source' => 'fallback',
            'sourceLabel' => 'Saved trail line',
        ];
    }

    /**
     * @param  array{lat: float, lng: float}  $from
     * @param  array{lat: float, lng: float}  $to
     */
    private function distanceKm(array $from, array $to): float
    {
        $earthRadiusKm = 6371.0;
        $latDelta = deg2rad($to['lat'] - $from['lat']);
        $lngDelta = deg2rad($to['lng'] - $from['lng']);
        $fromLat = deg2rad($from['lat']);
        $toLat = deg2rad($to['lat']);

        $a = sin($latDelta / 2) ** 2
            + cos($fromLat) * cos($toLat) * sin($lngDelta / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $path
     */
    private function polylineLengthKm(array $path): float
    {
        $distance = 0.0;

        for ($index = 1, $count = count($path); $index < $count; $index++) {
            $distance += $this->distanceKm($path[$index - 1], $path[$index]);
        }

        return $distance;
    }

    /**
     * @return array{south: string, west: string, north: string, east: string}
     */
    private function boundingBox(Mountain $mountain, float $paddingDegrees): array
    {
        $south = min((float) $mountain->jumpoff_lat, (float) $mountain->summit_lat) - $paddingDegrees;
        $north = max((float) $mountain->jumpoff_lat, (float) $mountain->summit_lat) + $paddingDegrees;
        $west = min((float) $mountain->jumpoff_lng, (float) $mountain->summit_lng) - $paddingDegrees;
        $east = max((float) $mountain->jumpoff_lng, (float) $mountain->summit_lng) + $paddingDegrees;

        return [
            'south' => number_format($south, 6, '.', ''),
            'west' => number_format($west, 6, '.', ''),
            'north' => number_format($north, 6, '.', ''),
            'east' => number_format($east, 6, '.', ''),
        ];
    }

    private function trailDataEnabled(): bool
    {
        $value = config('services.trail_data.enabled', true);

        if (is_bool($value)) {
            return $value;
        }

        return filter_var((string) $value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? true;
    }

    private function trailCacheHours(): int
    {
        return max(1, (int) config('services.trail_data.cache_hours', 12));
    }

    private function relationEndpointMatchThresholdKm(): float
    {
        return max(0.25, (float) config('services.trail_data.relation_endpoint_max_km', 1.5));
    }
}
