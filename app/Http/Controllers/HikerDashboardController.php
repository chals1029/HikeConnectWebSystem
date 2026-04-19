<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\CommunityPost;
use App\Models\HikeBooking;
use App\Models\Mountain;
use App\Models\MountainReview;
use App\Models\PackingItem;
use App\Models\TourGuide;
use App\Services\AchievementEvaluator;
use App\Services\EmailService;
use App\Services\TrailDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class HikerDashboardController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
        protected TrailDataService $trailDataService,
    ) {}

    public function index()
    {
        $user = Auth::user();
        $mountains = Mountain::query()->orderBy('sort_order')->get();
        $guides = TourGuide::query()->with('mountain')->orderBy('sort_order')->get();
        $bookings = HikeBooking::query()
            ->where('user_id', $user->id)
            ->with(['mountain', 'tourGuide', 'mountainReview'])
            ->orderByDesc('hike_on')
            ->orderByDesc('id')
            ->get();

        $completed = $bookings->where('status', 'completed');
        $defaultDuration = 4;
        $claimedBadgeCount = (int) DB::table('achievement_user')
            ->where('user_id', $user->id)
            ->whereNotNull('claimed_at')
            ->count();
        $stats = [
            'hikes_completed' => $completed->count(),
            'total_hours' => (int) round($completed->sum(fn (HikeBooking $b) => $b->duration_hours ?? $defaultDuration)),
            'total_elevation' => (int) $completed->sum(fn (HikeBooking $b) => $b->mountain->elevation_meters),
            'badges' => $claimedBadgeCount,
        ];

        $evaluator = new AchievementEvaluator($user);
        $achievementContext = $evaluator->buildContext();
        $claimedAchievementIds = DB::table('achievement_user')
            ->where('user_id', $user->id)
            ->whereNotNull('claimed_at')
            ->pluck('achievement_id');
        $achievementsUi = Achievement::query()->orderBy('sort_order')->get()->map(function (Achievement $a) use ($evaluator, $achievementContext, $claimedAchievementIds) {
            $claimed = $claimedAchievementIds->contains($a->id);
            $eligible = $evaluator->isEligible($a, $achievementContext);

            return [
                'id' => $a->id,
                'slug' => $a->slug,
                'name' => $a->name,
                'description' => $a->description,
                'badge_icon' => $a->badge_icon,
                'claimed' => $claimed,
                'eligible' => $eligible,
                'can_claim' => $eligible && ! $claimed,
            ];
        });

        $upcoming = HikeBooking::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('hike_on', '>=', today())
            ->with(['mountain', 'tourGuide'])
            ->orderBy('hike_on')
            ->first();

        $communityPosts = CommunityPost::query()->with('mountain')->latest()->limit(20)->get();
        $mountainReviews = MountainReview::query()->with('mountain')->latest()->get();
        $packingItems = PackingItem::query()->orderBy('category')->orderBy('sort_order')->get();

        $trailMountain = $upcoming?->mountain ?? $mountains->first();
        $focusMountainForWeather = $upcoming?->mountain ?? $mountains->first();
        $weatherLat = $focusMountainForWeather ? $focusMountainForWeather->meteoLat() : null;
        $weatherLng = $focusMountainForWeather ? $focusMountainForWeather->meteoLng() : null;

        $mountainData = $this->mountainsJsPayload($mountains, $mountainReviews);
        $guideData = $guides->keyBy('id')->map(fn (TourGuide $g) => [
            'name' => $g->full_name,
            'initials' => $g->initials,
            'spec' => $g->specialty,
            'mountain' => $g->mountain?->name ?? 'All Mountains',
            'mountainId' => $g->mountain?->slug ?? 'all',
            'status' => $g->status,
            'gradient' => $g->avatar_gradient,
        ])->all();

        $jumpoffMarkers = $mountains->map(fn (Mountain $m) => [
            'lat' => (float) $m->jumpoff_lat,
            'lng' => (float) $m->jumpoff_lng,
            'title' => $m->name.' Jump-off',
        ])->values()->all();

        $defaultJumpoff = $mountains->isEmpty()
            ? null
            : [
                'lat' => (float) ($upcoming?->mountain->jumpoff_lat ?? $mountains->first()->jumpoff_lat),
                'lng' => (float) ($upcoming?->mountain->jumpoff_lng ?? $mountains->first()->jumpoff_lng),
            ];

        $completedHistory = $bookings->where('status', 'completed')->sortByDesc('hike_on')->values();

        $safetyEmergency = (string) (
            $upcoming?->mountain->emergency_contact
            ?? $mountains->first()?->emergency_contact
            ?? env('HIKER_EMERGENCY_FALLBACK', '')
        );

        $mountainDifficulties = $mountains->pluck('difficulty')->filter()->unique()->sort()->values();
        $communityPostTotal = CommunityPost::query()->count();

        return view('hikers', compact(
            'user',
            'mountains',
            'guides',
            'bookings',
            'stats',
            'upcoming',
            'communityPosts',
            'packingItems',
            'trailMountain',
            'weatherLat',
            'weatherLng',
            'mountainData',
            'guideData',
            'jumpoffMarkers',
            'defaultJumpoff',
            'completedHistory',
            'safetyEmergency',
            'mountainDifficulties',
            'communityPostTotal',
            'achievementsUi',
        ));
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Mountain>  $mountains
     * @param  \Illuminate\Support\Collection<int, MountainReview>  $reviews
     * @return array<string, array<string, mixed>>
     */
    private function mountainsJsPayload($mountains, $reviews): array
    {
        $out = [];
        $reviewsByMountain = $reviews->groupBy('mountain_id');

        foreach ($mountains as $m) {
            $mountainReviews = $reviewsByMountain->get($m->id, collect())->values();
            $reviewSummary = $this->mountainReviewSummary($m, $mountainReviews);

            $out[$m->slug] = [
                'slug' => $m->slug,
                'name' => $m->name,
                'image' => asset($m->image_path),
                'status' => $m->status,
                'difficulty' => $m->difficulty,
                'rating' => (float) $m->rating,
                'location' => $m->location,
                'elevation' => $m->elevation_label,
                'duration' => $m->duration_label,
                'trailType' => $m->trail_type_label,
                'bestTime' => $m->best_time_label,
                'description' => $m->full_description,
                'jumpoff' => [
                    'name' => $m->jumpoff_name,
                    'address' => $m->jumpoff_address,
                    'meetingTime' => $m->jumpoff_meeting_time,
                    'notes' => (string) ($m->jumpoff_notes ?? ''),
                    'lat' => (float) $m->jumpoff_lat,
                    'lng' => (float) $m->jumpoff_lng,
                ],
                'summit' => [
                    'lat' => (float) $m->summit_lat,
                    'lng' => (float) $m->summit_lng,
                ],
                'weather' => [
                    'lat' => $m->meteoLat(),
                    'lng' => $m->meteoLng(),
                ],
                'gear' => $m->gear ?? [],
                'emergencyContact' => $m->emergency_contact,
                'reviews' => $reviewSummary,
                'experience' => $this->mountainExperiencePayload($m, $reviewSummary),
            ];
        }

        return $out;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, MountainReview>  $reviews
     * @return array<string, mixed>
     */
    private function mountainReviewSummary(Mountain $mountain, $reviews): array
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

    /**
     * @param  array<string, mixed>  $reviewSummary
     * @return array<string, mixed>
     */
    private function mountainExperiencePayload(Mountain $mountain, array $reviewSummary): array
    {
        $profiles = [
            'batulao' => [
                'subtitle' => 'Rolling ridges, open skies, and a summit push that still feels approachable with the right pacing.',
                'distanceKm' => 10.1,
                'elevationGainM' => 429,
                'routeType' => 'Out & back',
                'region' => 'Nasugbu, Batangas, Philippines',
                'gallery' => [
                    [
                        'image' => 'images/mt-batulao.jpg',
                        'label' => 'Jump-off to ridgeline',
                        'accent' => 'Open farmland approach',
                    ],
                    [
                        'image' => 'images/mt-batulao-2.jpg',
                        'label' => 'Summit ridges',
                        'accent' => 'Classic Batulao spine',
                    ],
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
                    [
                        'name' => 'Mount Batulao Summit',
                        'type' => 'Peak',
                        'description' => 'The final ridge push opens up to broad views over Nasugbu and neighboring ridgelines.',
                    ],
                    [
                        'name' => 'Camp 9',
                        'type' => 'Campsite',
                        'description' => 'A reliable regroup point for snacks, water checks, and a final summit push.',
                    ],
                    [
                        'name' => 'Old Trail Ridges',
                        'type' => 'Scenic section',
                        'description' => 'The most photogenic stretch of Batulao with exposed rolling ridges and airy viewpoints.',
                    ],
                ],
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
                'trailLabel' => 'Mount Batulao Trail',
            ],
            'pico' => [
                'subtitle' => 'A verified point-to-point Pico route from the old north jump-off, across the summit ridge, and down the south traverse exit.',
                'distanceKm' => 9.0,
                'elevationGainM' => 498,
                'routeType' => 'Point to point',
                'region' => 'Maragondon to Ternate, Cavite, Philippines',
                'gallery' => [
                    [
                        'image' => 'images/mt-pico-de-loro.jpg',
                        'label' => 'North approach',
                        'accent' => 'Base Camp 1 ascent',
                    ],
                    [
                        'image' => 'images/mt-pico-de-loro.jpg',
                        'label' => 'South traverse',
                        'accent' => 'Point-to-point descent',
                    ],
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
                    [
                        'name' => 'Base Camp 1',
                        'type' => 'Campsite',
                        'description' => 'The confirmed route passes the Base Camp 1 area before climbing toward the summit ridge.',
                    ],
                    [
                        'name' => 'Mount Pico de Loro Summit',
                        'type' => 'Peak',
                        'description' => 'The mountain’s signature rock tower is the star feature once you reach the summit area.',
                    ],
                    [
                        'name' => 'South Traverse Exit',
                        'type' => 'Trailhead',
                        'description' => 'The route finishes at the southern jump-off, so exit transport should be planned ahead.',
                    ],
                ],
                'routeEnd' => [
                    'name' => 'South exit',
                    'lat' => 14.2053049,
                    'lng' => 120.6277251,
                ],
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
                'trailLabel' => 'Mount Pico de Loro Point-to-Point Route',
            ],
            'talamitam' => [
                'subtitle' => 'A shorter grassland climb with broad views, rolling slopes, and a very approachable summit day.',
                'distanceKm' => 5.2,
                'elevationGainM' => 360,
                'routeType' => 'Out & back',
                'region' => 'Nasugbu, Batangas, Philippines',
                'gallery' => [
                    [
                        'image' => 'images/mt-talamitam.jpg',
                        'label' => 'Trail approach',
                        'accent' => 'Quick open climb',
                    ],
                    [
                        'image' => 'images/mt-talamitam.jpg',
                        'label' => 'Summit grasslands',
                        'accent' => 'Wide Batangas views',
                    ],
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
                    [
                        'name' => 'Mount Talamitam Summit',
                        'type' => 'Peak',
                        'description' => 'The summit opens to classic Batangas grassland views with Batulao visible on clear days.',
                    ],
                    [
                        'name' => 'Toong Jump-off',
                        'type' => 'Trailhead',
                        'description' => 'The mapped trailhead starts the route into the lower slopes and main grassland ascent.',
                    ],
                    [
                        'name' => 'Upper Ridge',
                        'type' => 'Scenic section',
                        'description' => 'A breezy open stretch that gives the climb its best photo spots before the summit.',
                    ],
                ],
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
                'trailLabel' => 'Mount Talamitam Trail',
            ],
        ];

        $profile = $profiles[$mountain->slug] ?? null;

        if (! $profile) {
            return ['enabled' => false];
        }

        $trailMap = ! empty($profile['forceSavedTrail'])
            ? [
                'label' => $profile['trailLabel'],
                'path' => $profile['fallbackTrailPath'],
                'source' => 'verified_saved',
                'sourceLabel' => 'Verified saved trail line',
            ]
            : $this->trailDataService->buildTrailMap(
                $mountain,
                $profile['trailLabel'],
                $profile['fallbackTrailPath'],
            );

        return [
            'enabled' => true,
            'subtitle' => $profile['subtitle'],
            'distanceKm' => $profile['distanceKm'],
            'elevationGainM' => $profile['elevationGainM'],
            'routeType' => $profile['routeType'],
            'region' => $profile['region'],
            'gallery' => [
                [
                    'image' => asset($profile['gallery'][0]['image']),
                    'label' => $profile['gallery'][0]['label'],
                    'accent' => $profile['gallery'][0]['accent'],
                ],
                [
                    'image' => asset($profile['gallery'][1]['image']),
                    'label' => $profile['gallery'][1]['label'],
                    'accent' => $profile['gallery'][1]['accent'],
                ],
            ],
            'routeMarkers' => $profile['routeMarkers'],
            'highlights' => $profile['highlights'],
            'topSights' => $profile['topSights'],
            'trailMap' => [...$trailMap],
            'routeEnd' => $profile['routeEnd'] ?? null,
            'conditions' => $profile['conditions'],
            'reviewBadge' => $reviewSummary['average'].' / 5 trail rating',
        ];
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'mountain' => ['required', 'string', 'exists:mountains,slug'],
            'tour_guide_id' => ['required', 'integer', 'exists:tour_guides,id'],
            'hike_on' => ['required', 'date', 'after_or_equal:today'],
            'hikers_count' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $mountain = Mountain::query()->where('slug', $validated['mountain'])->firstOrFail();
        $guide = TourGuide::query()->findOrFail($validated['tour_guide_id']);

        if ($guide->status !== 'available') {
            throw ValidationException::withMessages([
                'tour_guide_id' => ['This guide is not available for new bookings.'],
            ]);
        }

        if ($guide->mountain_id && $guide->mountain_id !== $mountain->id) {
            throw ValidationException::withMessages([
                'tour_guide_id' => ['This guide does not serve the selected mountain.'],
            ]);
        }

        HikeBooking::query()->create([
            'user_id' => Auth::id(),
            'mountain_id' => $mountain->id,
            'tour_guide_id' => $guide->id,
            'hike_on' => $validated['hike_on'],
            'hikers_count' => $validated['hikers_count'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true]);
    }

    public function cancelBooking(HikeBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($booking->status, ['pending', 'approved'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'This booking cannot be cancelled.',
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['success' => true]);
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['nullable', 'integer', 'exists:hike_bookings,id'],
            'mountain' => ['nullable', 'string', 'exists:mountains,slug'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! empty($validated['booking_id'])) {
            $booking = $this->resolveOwnedCompletedBooking((int) $validated['booking_id']);

            $review = MountainReview::query()->updateOrCreate(
                ['hike_booking_id' => $booking->id],
                [
                    'user_id' => Auth::id(),
                    'reviewer_name' => Auth::user()->full_name,
                    'rating' => $validated['rating'],
                    'body' => $validated['body'] ?? '',
                    'mountain_id' => $booking->mountain_id,
                ],
            );

            return response()->json([
                'success' => true,
                'review' => [
                    'rating' => (int) $review->rating,
                    'body' => (string) $review->body,
                ],
            ]);
        }

        if (empty($validated['mountain'])) {
            throw ValidationException::withMessages([
                'booking_id' => 'Choose a completed hike before sending mountain feedback.',
            ]);
        }

        $mountain = Mountain::query()->where('slug', $validated['mountain'])->firstOrFail();

        $review = MountainReview::query()->create([
            'user_id' => Auth::id(),
            'reviewer_name' => Auth::user()->full_name,
            'rating' => $validated['rating'],
            'body' => $validated['body'] ?? '',
            'mountain_id' => $mountain->id,
        ]);

        return response()->json([
            'success' => true,
            'review' => [
                'rating' => (int) $review->rating,
                'body' => (string) $review->body,
            ],
        ]);
    }

    public function storeGuideReview(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:hike_bookings,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking = $this->resolveOwnedCompletedBooking((int) $validated['booking_id']);

        $booking->update([
            'rating' => $validated['rating'],
            'review_text' => $validated['body'] ?? '',
        ]);

        return response()->json([
            'success' => true,
            'review' => [
                'rating' => (int) $booking->rating,
                'body' => (string) ($booking->review_text ?? ''),
            ],
        ]);
    }

    public function storeCommunityPost(Request $request)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:5000'],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
        ]);

        $user = Auth::user();

        CommunityPost::query()->create([
            'user_id' => $user->id,
            'author_name' => $user->full_name,
            'author_initials' => strtoupper(substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)),
            'body' => $validated['body'],
            'mountain_id' => $validated['mountain_id'] ?? null,
            'avatar_gradient' => 'linear-gradient(135deg,#065f46,#10b981)',
        ]);

        return response()->json(['success' => true]);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,gif,webp'],
        ]);

        $user = Auth::user();

        if ($user->profile_picture_path) {
            Storage::disk('public')->delete($user->profile_picture_path);
        }

        $path = $request->file('profile_picture')->store('profile-pictures/'.$user->id, 'public');
        $user->profile_picture_path = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'url' => $user->profile_picture_url,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'regex:/^[0-9+\-\s]{7,22}$/'],
            'bio' => ['nullable', 'string', 'max:5000'],
        ], [
            'phone.regex' => 'Enter a valid phone number.',
        ]);

        $user = Auth::user();
        $phone = $validated['phone'] ?? null;
        if ($phone === '') {
            $phone = null;
        }
        $user->fill([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $phone,
            'bio' => ($validated['bio'] ?? '') === '' ? null : $validated['bio'],
        ]);
        $user->save();

        return response()->json([
            'success' => true,
            'full_name' => $user->full_name,
        ]);
    }

    public function sendPasswordChangeCode(Request $request)
    {
        $user = Auth::user();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->password_change_code = Hash::make($code);
        $user->password_change_code_expires_at = now()->addMinutes(10);
        $user->save();

        $sent = $this->emailService->sendPasswordChangeCode($user->email, $code, $user->first_name);

        if (! $sent) {
            $user->password_change_code = null;
            $user->password_change_code_expires_at = null;
            $user->save();

            return response()->json([
                'success' => false,
                'message' => 'Could not send email. Check mail settings and try again.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'message' => 'We sent a 6-digit code to your email.',
        ]);
    }

    public function updatePasswordWithCode(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! $user->password_change_code || ! $user->password_change_code_expires_at || $user->password_change_code_expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Code expired or missing. Request a new code.',
            ], 422);
        }

        if (! Hash::check($validated['code'], $user->password_change_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.',
            ], 422);
        }

        $user->password = $validated['password'];
        $user->password_change_code = null;
        $user->password_change_code_expires_at = null;
        $user->save();

        $request->session()->regenerate();

        return response()->json(['success' => true]);
    }

    public function claimAchievement(Achievement $achievement)
    {
        $user = Auth::user();
        $evaluator = new AchievementEvaluator($user);
        $ctx = $evaluator->buildContext();

        if (! $evaluator->isEligible($achievement, $ctx)) {
            return response()->json([
                'success' => false,
                'message' => 'You have not completed this achievement yet.',
            ], 422);
        }

        $existing = $user->achievements()->where('achievements.id', $achievement->id)->first();
        if ($existing?->pivot?->claimed_at) {
            return response()->json([
                'success' => true,
                'already_claimed' => true,
                'badges_count' => $user->claimedAchievementsCount(),
            ]);
        }

        if ($existing) {
            $user->achievements()->updateExistingPivot($achievement->id, ['claimed_at' => now()]);
        } else {
            $user->achievements()->attach($achievement->id, ['claimed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'badges_count' => $user->claimedAchievementsCount(),
            'achievement_id' => $achievement->id,
        ]);
    }

    private function resolveOwnedCompletedBooking(int $bookingId): HikeBooking
    {
        $booking = HikeBooking::query()
            ->whereKey($bookingId)
            ->where('user_id', Auth::id())
            ->with(['mountain', 'tourGuide', 'mountainReview'])
            ->firstOrFail();

        if ($booking->status !== 'completed') {
            throw ValidationException::withMessages([
                'booking_id' => 'Only completed hikes can receive feedback.',
            ]);
        }

        return $booking;
    }
}
