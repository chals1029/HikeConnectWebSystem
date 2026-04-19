<?php

namespace Tests\Feature;

use App\Models\Mountain;
use App\Services\TrailDataService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrailDataServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'cache.default' => 'array',
            'services.trail_data.enabled' => true,
            'services.trail_data.overpass_url' => 'https://overpass.test/api/interpreter',
            'services.trail_data.cache_hours' => 1,
            'services.trail_data.relation_endpoint_max_km' => 1.5,
        ]);

        Cache::flush();
        Http::preventStrayRequests();
    }

    public function test_it_builds_a_trail_path_from_overpass_way_data(): void
    {
        Http::fake([
            'https://overpass.test/*' => Http::sequence()
                ->push([
                    'elements' => [],
                ], 200)
                ->push([
                    'elements' => [
                        [
                            'type' => 'way',
                            'nodes' => [101, 102, 103],
                            'geometry' => [
                                ['lat' => 14.0500, 'lon' => 120.6510],
                                ['lat' => 14.0477, 'lon' => 120.6488],
                                ['lat' => 14.0460, 'lon' => 120.6466],
                            ],
                        ],
                        [
                            'type' => 'way',
                            'nodes' => [103, 104, 105],
                            'geometry' => [
                                ['lat' => 14.0460, 'lon' => 120.6466],
                                ['lat' => 14.0444, 'lon' => 120.6418],
                                ['lat' => 14.0430, 'lon' => 120.6360],
                            ],
                        ],
                    ],
                ], 200),
        ]);

        $mountain = $this->makeMountain();
        $fallbackPath = [
            ['lat' => 14.0500, 'lng' => 120.6510],
            ['lat' => 14.0430, 'lng' => 120.6360],
        ];

        $map = app(TrailDataService::class)->buildTrailMap($mountain, 'Mount Batulao Trail', $fallbackPath);

        $this->assertSame('openstreetmap', $map['source']);
        $this->assertSame('Live trail geometry from OpenStreetMap', $map['sourceLabel']);
        $this->assertCount(5, $map['path']);
        $this->assertSame(14.0500, $map['path'][0]['lat']);
        $this->assertSame(120.6360, $map['path'][4]['lng']);
    }

    public function test_it_prefers_hiking_route_relations_when_available(): void
    {
        Http::fake([
            'https://overpass.test/*' => Http::sequence()
                ->push([
                    'elements' => [
                        [
                            'type' => 'relation',
                            'id' => 3350629,
                            'members' => [
                                ['type' => 'way', 'ref' => 11],
                                ['type' => 'way', 'ref' => 12],
                            ],
                            'tags' => [
                                'route' => 'hiking',
                                'name' => 'Mt. Batulao New to Old Trail',
                            ],
                        ],
                        [
                            'type' => 'way',
                            'id' => 11,
                            'geometry' => [
                                ['lat' => 14.0554825, 'lon' => 120.8205722],
                                ['lat' => 14.0538000, 'lon' => 120.8167000],
                                ['lat' => 14.0528757, 'lon' => 120.8129094],
                            ],
                        ],
                        [
                            'type' => 'way',
                            'id' => 12,
                            'geometry' => [
                                ['lat' => 14.0528757, 'lon' => 120.8129094],
                                ['lat' => 14.0479000, 'lon' => 120.8050000],
                                ['lat' => 14.0399434, 'lon' => 120.8023782],
                            ],
                        ],
                    ],
                ], 200)
                ->push([
                    'elements' => [],
                ], 200),
        ]);

        $mountain = new Mountain([
            'slug' => 'batulao',
            'name' => 'Mt. Batulao',
            'jumpoff_lat' => 14.0554825,
            'jumpoff_lng' => 120.8205722,
            'summit_lat' => 14.0399434,
            'summit_lng' => 120.8023782,
        ]);
        $fallbackPath = [
            ['lat' => 14.0554825, 'lng' => 120.8205722],
            ['lat' => 14.0399434, 'lng' => 120.8023782],
        ];

        $map = app(TrailDataService::class)->buildTrailMap($mountain, 'Mount Batulao Trail', $fallbackPath);

        $this->assertSame('openstreetmap', $map['source']);
        $this->assertSame('Live hiking route from OpenStreetMap', $map['sourceLabel']);
        $this->assertCount(5, $map['path']);
        $this->assertSame(14.0554825, $map['path'][0]['lat']);
        $this->assertSame(120.8023782, $map['path'][4]['lng']);
    }

    public function test_it_falls_back_to_saved_path_when_trail_api_fails(): void
    {
        Http::fake([
            'https://overpass.test/*' => Http::response([], 503),
        ]);

        $mountain = $this->makeMountain();
        $fallbackPath = [
            ['lat' => 14.0500, 'lng' => 120.6510],
            ['lat' => 14.0430, 'lng' => 120.6360],
        ];

        $map = app(TrailDataService::class)->buildTrailMap($mountain, 'Mount Batulao Trail', $fallbackPath);

        $this->assertSame('fallback', $map['source']);
        $this->assertSame('Saved trail line', $map['sourceLabel']);
        $this->assertSame($fallbackPath, $map['path']);
    }

    public function test_it_aligns_live_paths_to_the_exact_jump_off_and_summit_when_the_route_is_nearby(): void
    {
        Http::fake([
            'https://overpass.test/*' => Http::sequence()
                ->push([
                    'elements' => [
                        [
                            'type' => 'relation',
                            'id' => 3350625,
                            'members' => [
                                ['type' => 'way', 'ref' => 21],
                            ],
                            'tags' => [
                                'route' => 'hiking',
                                'name' => 'Mt. Talamitam Trail',
                            ],
                        ],
                        [
                            'type' => 'way',
                            'id' => 21,
                            'geometry' => [
                                ['lat' => 14.0919240, 'lon' => 120.7704844],
                                ['lat' => 14.0995618, 'lon' => 120.7645483],
                                ['lat' => 14.1080266, 'lon' => 120.7590149],
                            ],
                        ],
                    ],
                ], 200)
                ->push([
                    'elements' => [],
                ], 200),
        ]);

        $mountain = new Mountain([
            'slug' => 'talamitam',
            'name' => 'Mt. Talamitam',
            'jumpoff_lat' => 14.0885028,
            'jumpoff_lng' => 120.7760430,
            'summit_lat' => 14.1078115,
            'summit_lng' => 120.7599079,
        ]);

        $map = app(TrailDataService::class)->buildTrailMap($mountain, 'Mount Talamitam Trail', [
            ['lat' => 14.0885028, 'lng' => 120.7760430],
            ['lat' => 14.1078115, 'lng' => 120.7599079],
        ]);

        $this->assertSame('openstreetmap', $map['source']);
        $this->assertSame(14.0885028, $map['path'][0]['lat']);
        $this->assertSame(120.7760430, $map['path'][0]['lng']);
        $this->assertSame(14.1078115, $map['path'][count($map['path']) - 1]['lat']);
        $this->assertSame(120.7599079, $map['path'][count($map['path']) - 1]['lng']);
    }

    public function test_fallback_cache_does_not_replace_a_richer_saved_path_for_the_same_mountain(): void
    {
        Http::fake([
            'https://overpass.test/*' => Http::response([], 503),
        ]);

        $mountain = $this->makeMountain();
        $simpleFallback = [
            ['lat' => 14.0500, 'lng' => 120.6510],
            ['lat' => 14.0430, 'lng' => 120.6360],
        ];
        $richFallback = [
            ['lat' => 14.0500, 'lng' => 120.6510],
            ['lat' => 14.0480, 'lng' => 120.6485],
            ['lat' => 14.0460, 'lng' => 120.6442],
            ['lat' => 14.0430, 'lng' => 120.6360],
        ];

        $service = app(TrailDataService::class);

        $first = $service->buildTrailMap($mountain, 'Mount Batulao Trail', $simpleFallback);
        $second = $service->buildTrailMap($mountain, 'Mount Batulao Trail', $richFallback);

        $this->assertSame($simpleFallback, $first['path']);
        $this->assertSame($richFallback, $second['path']);
    }

    private function makeMountain(): Mountain
    {
        return new Mountain([
            'slug' => 'batulao',
            'name' => 'Mt. Batulao',
            'jumpoff_lat' => 14.0500,
            'jumpoff_lng' => 120.6510,
            'summit_lat' => 14.0430,
            'summit_lng' => 120.6360,
        ]);
    }
}
