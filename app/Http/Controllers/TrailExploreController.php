<?php

namespace App\Http\Controllers;

use App\Models\Mountain;
use App\Models\MountainReview;
use App\Models\TourGuide;
use App\Services\MountainTrailProfileService;

class TrailExploreController extends Controller
{
    public function __construct(
        protected MountainTrailProfileService $trailProfileService,
    ) {}

    /**
     * Public trail exploration page — no login required.
     * Shows full mountain/trail details so visitors can browse before deciding to book.
     */
    public function show(string $slug)
    {
        $mountain = Mountain::query()->where('slug', $slug)->firstOrFail();

        $reviews = MountainReview::query()
            ->where('mountain_id', $mountain->id)
            ->latest()
            ->get();

        $reviewSummary = $this->buildReviewSummary($mountain, $reviews);
        $experience = $this->buildExperiencePayload($mountain, $reviewSummary);

        $guides = TourGuide::query()
            ->where('status', 'available')
            ->where(function ($q) use ($mountain) {
                $q->whereNull('mountain_id')->orWhere('mountain_id', $mountain->id);
            })
            ->orderBy('sort_order')
            ->get();

        $mountainData = [
            'slug' => $mountain->slug,
            'name' => $mountain->name,
            'image' => asset($mountain->image_path),
            'status' => $mountain->status,
            'safetyStatus' => $mountain->safety_status ?? Mountain::SAFETY_OPEN,
            'safetyStatusLabel' => $mountain->safety_status_label,
            'safetyNote' => (string) ($mountain->safety_note ?? ''),
            'difficulty' => $mountain->difficulty,
            'rating' => (float) $mountain->rating,
            'location' => $mountain->location,
            'elevation' => $mountain->elevation_label,
            'duration' => $mountain->duration_label,
            'trailType' => $mountain->trail_type_label,
            'bestTime' => $mountain->best_time_label,
            'description' => $mountain->full_description,
            'jumpoff' => [
                'name' => $mountain->jumpoff_name,
                'address' => $mountain->jumpoff_address,
                'meetingTime' => $mountain->jumpoff_meeting_time,
                'notes' => (string) ($mountain->jumpoff_notes ?? ''),
                'lat' => (float) $mountain->jumpoff_lat,
                'lng' => (float) $mountain->jumpoff_lng,
            ],
            'summit' => [
                'lat' => (float) $mountain->summit_lat,
                'lng' => (float) $mountain->summit_lng,
            ],
            'weather' => [
                'lat' => $mountain->meteoLat(),
                'lng' => $mountain->meteoLng(),
            ],
            'gear' => $mountain->gear ?? [],
            'emergencyContact' => $mountain->emergency_contact,
            'pricing' => [
                'registrationFeePerPerson' => (float) ($mountain->registration_fee_per_person ?? 0),
                'environmentalFeePerPerson' => (float) ($mountain->environmental_fee_per_person ?? 0),
                'localFeePerPerson' => (float) ($mountain->local_fee_per_person ?? 0),
                'guideFeePerPerson' => (float) ($mountain->guide_fee_per_person ?? 0),
                'guideFeePerGroup' => (float) ($mountain->guide_fee_per_group ?? 0),
                'sourceNote' => (string) ($mountain->pricing_source_note ?? ''),
                'lastVerifiedOn' => optional($mountain->pricing_last_verified_on)->format('Y-m-d'),
            ],
            'reviews' => $reviewSummary,
            'experience' => $experience,
        ];

        $guideData = $guides->keyBy('id')->map(fn (TourGuide $g) => [
            'id' => $g->id,
            'name' => $g->full_name,
            'initials' => $g->initials,
            'spec' => $g->specialty,
            'mountain' => $g->mountain?->name ?? 'All Mountains',
            'mountainId' => $g->mountain?->slug ?? 'all',
            'status' => $g->status,
            'gradient' => $g->avatar_gradient,
        ])->values()->all();

        return view('trails.explore', [
            'mountain' => $mountain,
            'mountainData' => $mountainData,
            'guideData' => $guideData,
            'reviewSummary' => $reviewSummary,
        ]);
    }

    private function buildReviewSummary(Mountain $mountain, $reviews): array
    {
        $count = $reviews->count();
        $average = $count > 0
            ? round((float) $reviews->avg('rating'), 1)
            : round((float) $mountain->rating, 1);

        $distribution = [];
        foreach ([5, 4, 3, 2, 1] as $score) {
            $distribution[(string) $score] = (int) $reviews->where('rating', $score)->count();
        }

        return [
            'average' => $average,
            'count' => $count,
            'distribution' => $distribution,
            'items' => $reviews
                ->take(4)
                ->map(fn (MountainReview $review) => [
                    'reviewer' => $review->reviewer_name,
                    'rating' => (int) $review->rating,
                    'body' => $review->body,
                    'date' => optional($review->created_at)->format('M j, Y'),
                ])
                ->values()
                ->all(),
        ];
    }

    private function buildExperiencePayload(Mountain $mountain, array $reviewSummary): array
    {
        $profiles = [
            'batulao' => [
                'subtitle' => 'Rolling ridges, open skies, and a summit push that still feels approachable with the right pacing.',
                'distanceKm' => 10.1,
                'elevationGainM' => 429,
                'routeType' => 'Out & back',
                'region' => 'Nasugbu, Batangas, Philippines',
                'gallery' => [
                    ['image' => 'images/mt-batulao.jpg', 'label' => 'Jump-off to ridgeline', 'accent' => 'Open farmland approach'],
                    ['image' => 'images/mt-batulao-2.jpg', 'label' => 'Summit ridges', 'accent' => 'Classic Batulao spine'],
                ],
                'routeMarkers' => [
                    ['name' => 'Jump-off', 'detail' => '0.0 km'],
                    ['name' => 'Camp 9', 'detail' => '3.7 km'],
                    ['name' => 'Summit', 'detail' => '5.0 km'],
                ],
                'highlights' => [
                    'Open grassland ridges with sunrise-friendly views',
                    'Short summit reward that works well for day hikers',
                    'Clear trail flow with guide, registration, and regroup points',
                ],
                'topSights' => [
                    ['name' => 'Mount Batulao Summit', 'type' => 'Peak', 'description' => 'The final ridge push opens up to broad views over Nasugbu and neighboring ridgelines.'],
                    ['name' => 'Camp 9', 'type' => 'Campsite', 'description' => 'A reliable regroup point for snacks, water checks, and a final summit push.'],
                    ['name' => 'Old Trail Ridges', 'type' => 'Scenic section', 'description' => 'The most photogenic stretch of Batulao with exposed rolling ridges and airy viewpoints.'],
                ],
                'conditions' => [
                    'crowdLabel' => 'Crowds build fast on weekends',
                    'shadeLabel' => 'Low shade after sunrise',
                    'surfaceLabel' => 'Loose soil on descent',
                    'summary' => 'Best tackled early: the exposed trail warms up quickly, and loose soil needs slower footing after rain.',
                    'tips' => [
                        'Start before 6:00 AM to beat the heat on the ridges.',
                        'Carry at least 2L of water plus electrolytes for the exposed sections.',
                        'Use shorter downhill steps when the trail is dusty or recently wet.',
                    ],
                ],
            ],
            'pico' => [
                'subtitle' => 'A verified point-to-point Pico route from the old north jump-off, across the summit ridge, and down the south traverse exit.',
                'distanceKm' => 9.0,
                'elevationGainM' => 498,
                'routeType' => 'Point to point',
                'region' => 'Maragondon to Ternate, Cavite, Philippines',
                'gallery' => [
                    ['image' => 'images/mt-pico-de-loro.jpg', 'label' => 'North approach', 'accent' => 'Base Camp 1 ascent'],
                    ['image' => 'images/mt-pico-de-loro.jpg', 'label' => 'South traverse', 'accent' => 'Point-to-point descent'],
                ],
                'routeMarkers' => [
                    ['name' => 'North jump-off', 'detail' => '0.0 km'],
                    ['name' => 'Summit ridge', 'detail' => '4.5 km'],
                    ['name' => 'South exit', 'detail' => '9.0 km'],
                ],
                'highlights' => [
                    'Verified point-to-point route that crosses Pico from the north approach to the south traverse exit',
                    'Base Camp 1 and the summit ridge stay on the main line before the trail drops into the longer traverse descent',
                    'Best for hikers who have arranged transport at both ends instead of returning the same way',
                ],
                'topSights' => [
                    ['name' => 'Base Camp 1', 'type' => 'Campsite', 'description' => 'The confirmed route passes the Base Camp 1 area before climbing toward the summit ridge.'],
                    ['name' => 'Mount Pico de Loro Summit', 'type' => 'Peak', 'description' => "The mountain's signature rock tower is the star feature once you reach the summit area."],
                    ['name' => 'South Traverse Exit', 'type' => 'Trailhead', 'description' => 'The route finishes at the southern jump-off, so exit transport should be planned ahead.'],
                ],
                'conditions' => [
                    'crowdLabel' => 'Popular on weekends',
                    'shadeLabel' => 'Good shade early, more exposed on the traverse side',
                    'surfaceLabel' => 'Forest footpath, summit rock, then long traverse descent',
                    'summary' => 'This confirmed Pico route is a full crossing, so hikers need to be ready for both the summit section and the much longer south-side exit.',
                    'tips' => [
                        'Arrange transport at both ends because this route does not return to the same jump-off.',
                        'Expect the summit ridge to sit around the middle of the route, not at the finish.',
                        'Carry enough water for the long south descent even if the early forest section feels manageable.',
                    ],
                ],
            ],
            'talamitam' => [
                'subtitle' => 'A shorter grassland climb with broad views, rolling slopes, and a very approachable summit day.',
                'distanceKm' => 5.2,
                'elevationGainM' => 360,
                'routeType' => 'Out & back',
                'region' => 'Nasugbu, Batangas, Philippines',
                'gallery' => [
                    ['image' => 'images/mt-talamitam.jpg', 'label' => 'Trail approach', 'accent' => 'Quick open climb'],
                    ['image' => 'images/mt-talamitam.jpg', 'label' => 'Summit grasslands', 'accent' => 'Wide Batangas views'],
                ],
                'routeMarkers' => [
                    ['name' => 'Jump-off', 'detail' => '0.0 km'],
                    ['name' => 'Grassland ridge', 'detail' => '1.4 km'],
                    ['name' => 'Summit', 'detail' => '2.6 km'],
                ],
                'highlights' => [
                    'Shorter route that still delivers big ridgeline views and sunrise-friendly timing',
                    'Mostly open terrain makes it excellent for beginner conditioning hikes',
                    'Clear trail flow from the jump-off into the upper grassland ridge',
                ],
                'topSights' => [
                    ['name' => 'Mount Talamitam Summit', 'type' => 'Peak', 'description' => 'The summit opens to classic Batangas grassland views with Batulao visible on clear days.'],
                    ['name' => 'Toong Jump-off', 'type' => 'Trailhead', 'description' => 'The mapped trailhead starts the route into the lower slopes and main grassland ascent.'],
                    ['name' => 'Upper Ridge', 'type' => 'Scenic section', 'description' => 'A breezy open stretch that gives the climb its best photo spots before the summit.'],
                ],
                'conditions' => [
                    'crowdLabel' => 'Light to moderate traffic',
                    'shadeLabel' => 'Very low shade after base',
                    'surfaceLabel' => 'Dry grassland and dusty sections',
                    'summary' => 'Talamitam climbs fast into exposed grassland, so the route feels easiest very early before the sun and wind fully pick up.',
                    'tips' => [
                        'Use a cap and sunscreen early because shade disappears quickly after the first section.',
                        'The route is shorter, but exposed heat can still drain water faster than expected.',
                        'Wind can be stronger on the upper ridge, so keep loose items secured before summit photos.',
                    ],
                ],
            ],
        ];

        $profile = $profiles[$mountain->slug] ?? null;

        if (! $profile) {
            return $this->buildDefaultExperiencePayload($mountain, $reviewSummary);
        }

        $trailMap = $this->trailProfileService->buildTrailMap($mountain);
        $routeEnd = $this->trailProfileService->routeEnd($mountain->slug);

        return [
            'enabled' => true,
            'subtitle' => $profile['subtitle'],
            'distanceKm' => $profile['distanceKm'],
            'elevationGainM' => $profile['elevationGainM'],
            'routeType' => $profile['routeType'],
            'region' => $profile['region'],
            'gallery' => [
                ['image' => asset($profile['gallery'][0]['image']), 'label' => $profile['gallery'][0]['label'], 'accent' => $profile['gallery'][0]['accent']],
                ['image' => asset($profile['gallery'][1]['image']), 'label' => $profile['gallery'][1]['label'], 'accent' => $profile['gallery'][1]['accent']],
            ],
            'routeMarkers' => $profile['routeMarkers'],
            'highlights' => $profile['highlights'],
            'topSights' => $profile['topSights'],
            'trailMap' => [...$trailMap],
            'routeEnd' => $routeEnd,
            'conditions' => $profile['conditions'],
            'reviewBadge' => $reviewSummary['average'] . ' / 5 trail rating',
        ];
    }

    private function buildDefaultExperiencePayload(Mountain $mountain, array $reviewSummary): array
    {
        $difficulty = strtolower((string) $mountain->difficulty);
        $estimatedDistance = max(2.5, round((float) ($mountain->elevation_meters / 170), 1));

        $trailMap = $this->trailProfileService->buildTrailMap($mountain);
        $routeEnd = $this->trailProfileService->routeEnd($mountain->slug);

        return [
            'enabled' => true,
            'subtitle' => $mountain->short_description ?: 'Trail overview, conditions, and field tips for a safer hike day.',
            'distanceKm' => $estimatedDistance,
            'elevationGainM' => (int) $mountain->elevation_meters,
            'routeType' => str_contains(strtolower($mountain->trail_type_label ?? ''), 'open') ? 'Out & back' : 'Point to point',
            'region' => $mountain->location,
            'gallery' => [
                ['image' => asset($mountain->image_path), 'label' => $mountain->jumpoff_name ?: 'Jump-off', 'accent' => 'Trail approach'],
                ['image' => asset($mountain->image_path), 'label' => $mountain->name . ' summit route', 'accent' => $mountain->trail_type_label ?? 'Trail'],
            ],
            'routeMarkers' => [
                ['name' => 'Jump-off', 'detail' => '0.0 km'],
                ['name' => 'Mid trail', 'detail' => number_format($estimatedDistance / 2, 1) . ' km'],
                ['name' => 'Summit', 'detail' => number_format($estimatedDistance, 1) . ' km'],
            ],
            'highlights' => [
                'Trail profile: ' . ($mountain->trail_type_label ?? 'Mixed') . ' with ' . $mountain->difficulty . ' pacing.',
                'Meet-up starts at ' . $mountain->jumpoff_name . ' (' . $mountain->jumpoff_meeting_time . ').',
                'Summit day timing works best with early-start hydration and pacing checks.',
            ],
            'topSights' => [
                ['name' => $mountain->jumpoff_name ?? 'Trailhead', 'type' => 'Trailhead', 'description' => $mountain->jumpoff_address ?? ''],
                ['name' => $mountain->name . ' Summit', 'type' => 'Peak', 'description' => 'Main summit area with elevation at ' . $mountain->elevation_label . '.'],
                ['name' => 'Trail route', 'type' => 'Scenic section', 'description' => 'Expected terrain: ' . ($mountain->trail_type_label ?? 'Mixed') . '.'],
            ],
            'trailMap' => [...$trailMap],
            'routeEnd' => $routeEnd,
            'conditions' => [
                'crowdLabel' => 'Light to moderate traffic depending on weather',
                'shadeLabel' => 'Mixed shade depending on trail section',
                'surfaceLabel' => 'Mixed soil trail with occasional loose sections',
                'summary' => 'Best tackled early with steady pacing, regular hydration, and careful footing on exposed or loose sections.',
                'tips' => [
                    'Start 30-60 minutes before the listed meeting time to settle permits and warm up.',
                    'Bring enough water for ' . ($mountain->duration_label ?? '4-5 hours') . ' and add electrolytes for exposed sections.',
                    'Use short, controlled steps on steep descents and avoid rushing loose ground.',
                ],
            ],
            'reviewBadge' => $reviewSummary['average'] . ' / 5 trail rating',
        ];
    }
}
