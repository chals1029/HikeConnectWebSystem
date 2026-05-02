<?php

namespace App\Services;

use App\Models\Mountain;

/**
 * Single source of truth for the verified, hand-traced trail polylines that
 * power both the hiker mountain-detail page (the yellow/blue "fence") and
 * the admin live map. Without this, the admin map would only know the
 * jump-off and summit endpoints and would draw a straight line instead of
 * the actual curvy mountain trail.
 */
class MountainTrailProfileService
{
    /**
     * Per-slug trail profile: label, the verified saved waypoints that match
     * the real mountain trail, and (optionally) a route-end pin for
     * point-to-point routes.
     *
     * `forceSavedTrail = true` means the saved waypoints ARE the canonical
     * trail line — we render them as-is and skip the OpenStreetMap Overpass
     * lookup. Otherwise the saved waypoints are used as a fallback when
     * Overpass fails or returns an unusable result.
     *
     * @var array<string, array{
     *     trailLabel: string,
     *     forceSavedTrail: bool,
     *     fallbackTrailPath: array<int, array{lat: float, lng: float}>,
     *     routeEnd?: array{name: string, lat: float, lng: float}
     * }>
     */
    private array $profiles = [
        'batulao' => [
            'trailLabel' => 'Mount Batulao Trail',
            'forceSavedTrail' => true,
            'fallbackTrailPath' => [
                ['lat' => 14.0581288, 'lng' => 120.8313422],
                ['lat' => 14.0577855, 'lng' => 120.8292876],
                ['lat' => 14.0567855, 'lng' => 120.8270297],
                ['lat' => 14.0553735, 'lng' => 120.8239923],
                ['lat' => 14.0554825, 'lng' => 120.8205722],
                ['lat' => 14.0557017, 'lng' => 120.8179019],
                ['lat' => 14.0538852, 'lng' => 120.8150767],
                ['lat' => 14.0528236, 'lng' => 120.8116542],
                ['lat' => 14.0521778, 'lng' => 120.8090893],
                ['lat' => 14.0518763, 'lng' => 120.8075410],
                ['lat' => 14.0506692, 'lng' => 120.8061288],
                ['lat' => 14.0482962, 'lng' => 120.8048414],
                ['lat' => 14.0458983, 'lng' => 120.8031549],
                ['lat' => 14.0437651, 'lng' => 120.8017493],
                ['lat' => 14.0428554, 'lng' => 120.8014918],
                ['lat' => 14.0420889, 'lng' => 120.8015545],
                ['lat' => 14.0413709, 'lng' => 120.8011945],
                ['lat' => 14.0403453, 'lng' => 120.8017539],
                ['lat' => 14.0399434, 'lng' => 120.8023782],
                ['lat' => 14.0403315, 'lng' => 120.8025741],
                ['lat' => 14.0405860, 'lng' => 120.8027830],
                ['lat' => 14.0408104, 'lng' => 120.8029848],
                ['lat' => 14.0409675, 'lng' => 120.8034532],
                ['lat' => 14.0408267, 'lng' => 120.8040761],
                ['lat' => 14.0406221, 'lng' => 120.8050627],
                ['lat' => 14.0410548, 'lng' => 120.8058052],
                ['lat' => 14.0419719, 'lng' => 120.8061882],
                ['lat' => 14.0437056, 'lng' => 120.8072562],
                ['lat' => 14.0440662, 'lng' => 120.8078081],
                ['lat' => 14.0450522, 'lng' => 120.8080054],
                ['lat' => 14.0463915, 'lng' => 120.8063455],
                ['lat' => 14.0476218, 'lng' => 120.8066821],
                ['lat' => 14.0488711, 'lng' => 120.8067467],
                ['lat' => 14.0505569, 'lng' => 120.8066738],
                ['lat' => 14.0514327, 'lng' => 120.8069371],
            ],
        ],
        'pico' => [
            'trailLabel' => 'Mount Pico de Loro Point-to-Point Route',
            'forceSavedTrail' => true,
            'fallbackTrailPath' => [
                ['lat' => 14.2343636, 'lng' => 120.6597593],
                ['lat' => 14.2351196, 'lng' => 120.6610751],
                ['lat' => 14.2353240, 'lng' => 120.6624207],
                ['lat' => 14.2345608, 'lng' => 120.6632659],
                ['lat' => 14.2331706, 'lng' => 120.6628584],
                ['lat' => 14.2319643, 'lng' => 120.6633617],
                ['lat' => 14.2302034, 'lng' => 120.6639802],
                ['lat' => 14.2289519, 'lng' => 120.6650666],
                ['lat' => 14.2277032, 'lng' => 120.6644697],
                ['lat' => 14.2263038, 'lng' => 120.6640444],
                ['lat' => 14.2248007, 'lng' => 120.6639792],
                ['lat' => 14.2235035, 'lng' => 120.6629972],
                ['lat' => 14.2221673, 'lng' => 120.6620941],
                ['lat' => 14.2209402, 'lng' => 120.6608698],
                ['lat' => 14.2200745, 'lng' => 120.6605545],
                ['lat' => 14.2187317, 'lng' => 120.6608708],
                ['lat' => 14.2170358, 'lng' => 120.6603908],
                ['lat' => 14.2157786, 'lng' => 120.6598377],
                ['lat' => 14.2146666, 'lng' => 120.6585075],
                ['lat' => 14.2153847, 'lng' => 120.6574878],
                ['lat' => 14.2157760, 'lng' => 120.6564685],
                ['lat' => 14.2159671, 'lng' => 120.6554129],
                ['lat' => 14.2165428, 'lng' => 120.6543429],
                ['lat' => 14.2168664, 'lng' => 120.6535584],
                ['lat' => 14.2173838, 'lng' => 120.6526748],
                ['lat' => 14.2169503, 'lng' => 120.6519177],
                ['lat' => 14.2166776, 'lng' => 120.6508114],
                ['lat' => 14.2154855, 'lng' => 120.6476085],
                ['lat' => 14.2143619, 'lng' => 120.6463951],
                ['lat' => 14.2128013, 'lng' => 120.6452045],
                ['lat' => 14.2104144, 'lng' => 120.6441477],
                ['lat' => 14.2084460, 'lng' => 120.6435227],
                ['lat' => 14.2063856, 'lng' => 120.6409183],
                ['lat' => 14.2054340, 'lng' => 120.6352938],
                ['lat' => 14.2062452, 'lng' => 120.6293580],
                ['lat' => 14.2053049, 'lng' => 120.6277251],
            ],
            'routeEnd' => [
                'name' => 'South exit',
                'lat' => 14.2053049,
                'lng' => 120.6277251,
            ],
        ],
        'talamitam' => [
            'trailLabel' => 'Mount Talamitam Trail',
            'forceSavedTrail' => true,
            'fallbackTrailPath' => [
                ['lat' => 14.0885028, 'lng' => 120.7760430],
                ['lat' => 14.0919240, 'lng' => 120.7704844],
                ['lat' => 14.0925509, 'lng' => 120.7690517],
                ['lat' => 14.0941300, 'lng' => 120.7674451],
                ['lat' => 14.0968901, 'lng' => 120.7664875],
                ['lat' => 14.0995618, 'lng' => 120.7645483],
                ['lat' => 14.1047698, 'lng' => 120.7628290],
                ['lat' => 14.1080266, 'lng' => 120.7590149],
                ['lat' => 14.1078115, 'lng' => 120.7599079],
            ],
        ],
    ];

    public function __construct(protected TrailDataService $trailDataService)
    {
    }

    /**
     * @return array{trailLabel: string, forceSavedTrail: bool, fallbackTrailPath: array<int, array{lat: float, lng: float}>, routeEnd?: array{name: string, lat: float, lng: float}}|null
     */
    public function profile(?string $slug): ?array
    {
        if (! $slug) {
            return null;
        }

        return $this->profiles[$slug] ?? null;
    }

    /**
     * @return array{name: string, lat: float, lng: float}|null
     */
    public function routeEnd(?string $slug): ?array
    {
        return $this->profile($slug)['routeEnd'] ?? null;
    }

    /**
     * Build the trail map both the mountain-detail page and the admin live
     * map render. When a mountain has a verified saved curvy path we use it
     * directly; otherwise we delegate to TrailDataService and pass the
     * saved path as fallback so OSM failures still produce an accurate
     * line instead of a straight jumpoff→summit segment.
     *
     * @return array{label: string, path: array<int, array{lat: float, lng: float}>, source: string, sourceLabel: string}
     */
    public function buildTrailMap(Mountain $mountain): array
    {
        $profile = $this->profile($mountain->slug);
        $label = $profile['trailLabel'] ?? ($mountain->name.' Trail');
        $fallback = $profile['fallbackTrailPath'] ?? [];

        if (! empty($profile['forceSavedTrail']) && count($fallback) > 1) {
            return [
                'label' => $label,
                'path' => $fallback,
                'source' => 'verified_saved',
                'sourceLabel' => 'Verified saved trail line',
            ];
        }

        return $this->trailDataService->buildTrailMap($mountain, $label, $fallback);
    }
}
