<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\CommunityPost;
use App\Models\CommunityPostComment;
use App\Models\CommunityPostLike;
use App\Models\HikeBooking;
use App\Models\HikerLocation;
use App\Models\Mountain;
use App\Models\MountainReview;
use App\Models\PackingItem;
use App\Models\SosAlert;
use App\Models\TourGuide;
use App\Models\User;
use App\Models\UserExperienceFeedback;
use App\Services\AchievementEvaluator;
use App\Services\AuditLogger;
use App\Services\EmailService;
use App\Services\MountainTrailProfileService;
use App\Services\NotificationDispatcher;
use App\Services\ProfilePictureDatabaseWriter;
use App\Services\TrailDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class HikerDashboardController extends Controller
{
    public function __construct(
        protected EmailService $emailService,
        protected TrailDataService $trailDataService,
        protected MountainTrailProfileService $trailProfileService,
    ) {}

    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isTourGuide()) {
            return redirect()->route('tour-guide.dashboard');
        }
        $profilePictureRelation = User::supportsDatabaseProfilePictures()
            ? 'tourGuide.user.profilePicture:user_id,mime,updated_at'
            : null;
        $guideUserPictureRelation = User::supportsDatabaseProfilePictures()
            ? 'user.profilePicture:user_id,mime,updated_at'
            : null;
        $mountains = Mountain::query()->orderBy('sort_order')->get();
        $guides = TourGuide::query()
            ->with(array_filter([
                'mountain',
                'user:id,first_name,last_name,email,phone,profile_picture_path',
                $guideUserPictureRelation,
            ]))
            ->orderBy('sort_order')
            ->get();
        $bookings = HikeBooking::query()
            ->where('user_id', $user->id)
            ->with(array_filter([
                'mountain',
                'tourGuide',
                'mountainReview',
                'tourGuide.user:id,first_name,last_name,email,phone,profile_picture_path',
                $profilePictureRelation,
            ]))
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
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->whereDate('hike_on', '>=', today())
            ->with(array_filter([
                'mountain',
                'tourGuide',
                'tourGuide.user:id,first_name,last_name,email,phone,profile_picture_path',
                $profilePictureRelation,
            ]))
            ->orderBy('hike_on')
            ->first();

        $communityPosts = CommunityPost::query()
            ->with(array_filter([
                'mountain',
                User::supportsDatabaseProfilePictures() ? 'user.profilePicture' : 'user',
            ]))
            ->withCount(['likes', 'comments'])
            ->latest()
            ->limit(20)
            ->get();

        // Posts the current user has already liked, so the UI can render the
        // "Liked" state on first paint without an extra roundtrip.
        $likedPostIds = $communityPosts->isEmpty()
            ? collect()
            : CommunityPostLike::query()
                ->where('user_id', $user->id)
                ->whereIn('community_post_id', $communityPosts->pluck('id'))
                ->pluck('community_post_id');
        $communityPosts->each(function ($post) use ($likedPostIds) {
            $post->liked_by_me = $likedPostIds->contains($post->id);
        });
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
            'photo' => $g->profile_picture_url,
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
        $hasSubmittedExperienceFeedback = UserExperienceFeedback::query()
            ->where('user_id', $user->id)
            ->where('context', 'hiker_dashboard_login')
            ->exists();
        $safetyMountains = $mountains
            ->filter(fn (Mountain $m) => $m->hasSafetyWarning())
            ->values();
        $hikerSosAlerts = SosAlert::query()
            ->where('user_id', $user->id)
            ->with([
                'mountain:id,name,location,emergency_contact',
                'hikeBooking:id,hike_on,status,mountain_id,tour_guide_id',
                'hikeBooking.mountain:id,name,location,emergency_contact',
                'tourGuide:id,first_name,last_name,email,phone',
                'acknowledgedBy:id,first_name,last_name',
                'resolvedBy:id,first_name,last_name',
            ])
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 WHEN status = 'acknowledged' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        // ----------------------------------------------------------------
        // Hiker leaderboard — rank by completed hikes, tie-break with
        // total logged hours then total elevation gained.
        // ----------------------------------------------------------------
        [$leaderboard, $myRank, $myLeaderRow, $totalHikers] = $this->buildHikerLeaderboard($user);

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
            'hasSubmittedExperienceFeedback',
            'safetyMountains',
            'hikerSosAlerts',
            'achievementsUi',
            'leaderboard',
            'myRank',
            'myLeaderRow',
            'totalHikers',
        ));
    }

    /**
     * Build the hiker leaderboard, the current user's rank entry, and the
     * total number of hikers that participate in the ranking.
     *
     * @return array{0: \Illuminate\Support\Collection<int, array<string, mixed>>, 1: ?int, 2: ?array<string, mixed>, 3: int}
     */
    private function buildHikerLeaderboard(User $user): array
    {
        $hasAvatarTable = User::supportsDatabaseProfilePictures();

        $base = DB::table('users as u')
            ->leftJoin('hike_bookings as b', function ($j) {
                $j->on('b.user_id', '=', 'u.id')->where('b.status', 'completed');
            })
            ->leftJoin('mountains as m', 'm.id', '=', 'b.mountain_id')
            ->where(function ($q) {
                $q->where('u.role', User::ROLE_HIKER)->orWhereNull('u.role');
            });

        if ($hasAvatarTable) {
            $base->leftJoin('user_profile_pictures as upp', 'upp.user_id', '=', 'u.id')
                ->groupBy('u.id', 'u.first_name', 'u.last_name', 'u.profile_picture_path', 'u.created_at', 'upp.user_id')
                ->select('u.id', 'u.first_name', 'u.last_name', 'u.profile_picture_path', 'u.created_at', 'upp.user_id as profile_picture_db_user');
        } else {
            $base->groupBy('u.id', 'u.first_name', 'u.last_name', 'u.profile_picture_path', 'u.created_at')
                ->select('u.id', 'u.first_name', 'u.last_name', 'u.profile_picture_path', 'u.created_at');
        }

        $rows = $base
            ->selectRaw('COUNT(b.id) as hikes_completed')
            ->selectRaw('COALESCE(SUM(COALESCE(b.duration_hours, 4)), 0) as total_hours')
            ->selectRaw('COALESCE(SUM(COALESCE(m.elevation_meters, 0)), 0) as total_elevation')
            ->orderByDesc('hikes_completed')
            ->orderByDesc('total_hours')
            ->orderByDesc('total_elevation')
            ->orderBy('u.id')
            ->get();

        $entries = $rows->values()->map(function ($r, $idx) use ($hasAvatarTable) {
            $first = (string) ($r->first_name ?? '');
            $last  = (string) ($r->last_name ?? '');
            $full  = trim($first.' '.$last);
            $initials = strtoupper(
                ($first !== '' ? substr($first, 0, 1) : '').
                ($last  !== '' ? substr($last,  0, 1) : '')
            );
            if ($initials === '') {
                $initials = 'HC';
            }

            $picUrl = null;
            if ($hasAvatarTable && ! empty($r->profile_picture_db_user ?? null)) {
                $picUrl = '/avatars/'.(int) $r->id;
            } elseif (! empty($r->profile_picture_path)) {
                $relative = 'storage/'.ltrim(str_replace('\\', '/', $r->profile_picture_path), '/');
                $picUrl = asset($relative);
            }

            return [
                'rank'            => $idx + 1,
                'id'              => (int) $r->id,
                'first_name'      => $first,
                'last_name'       => $last,
                'full_name'       => $full !== '' ? $full : 'Hiker',
                'initials'        => $initials,
                'profile_picture' => $picUrl,
                'hikes_completed' => (int) $r->hikes_completed,
                'total_hours'     => (int) round((float) $r->total_hours),
                'total_elevation' => (int) round((float) $r->total_elevation),
                'joined_at'       => $r->created_at,
            ];
        });

        $myRank = null;
        $myRow  = null;
        foreach ($entries as $entry) {
            if ($entry['id'] === $user->id) {
                $myRank = $entry['rank'];
                $myRow  = $entry;
                break;
            }
        }

        return [$entries, $myRank, $myRow, $entries->count()];
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
                'safetyStatus' => $m->safety_status ?? Mountain::SAFETY_OPEN,
                'safetyStatusLabel' => $m->safety_status_label,
                'safetyNote' => (string) ($m->safety_note ?? ''),
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
                'pricing' => [
                    'registrationFeePerPerson' => (float) ($m->registration_fee_per_person ?? 0),
                    'environmentalFeePerPerson' => (float) ($m->environmental_fee_per_person ?? 0),
                    'localFeePerPerson' => (float) ($m->local_fee_per_person ?? 0),
                    'guideFeePerPerson' => (float) ($m->guide_fee_per_person ?? 0),
                    'guideFeePerGroup' => (float) ($m->guide_fee_per_group ?? 0),
                    'sourceNote' => (string) ($m->pricing_source_note ?? ''),
                    'lastVerifiedOn' => optional($m->pricing_last_verified_on)->format('Y-m-d'),
                ],
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
            return $this->buildDefaultMountainExperiencePayload($mountain, $reviewSummary);
        }

        // Trail polyline + route-end pin live in MountainTrailProfileService
        // so the admin live map and this mountain-detail page share one
        // source of truth for the curvy "fence" line.
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
            'routeEnd' => $routeEnd,
            'conditions' => $profile['conditions'],
            'reviewBadge' => $reviewSummary['average'].' / 5 trail rating',
        ];
    }

    /**
     * Build a strong default "trail spotlight" payload so every mountain
     * has usable conditions/tips even without a custom static profile.
     *
     * @param  array<string, mixed>  $reviewSummary
     * @return array<string, mixed>
     */
    private function buildDefaultMountainExperiencePayload(Mountain $mountain, array $reviewSummary): array
    {
        $difficulty = strtolower((string) $mountain->difficulty);
        $estimatedDistance = max(2.5, round((float) ($mountain->elevation_meters / 170), 1));
        $crowdLabel = match (true) {
            str_contains($difficulty, 'hard') => 'Moderate to heavy on good-weather weekends',
            str_contains($difficulty, 'easy') => 'Steady traffic from early morning',
            default => 'Light to moderate traffic depending on weather',
        };
        $shadeLabel = match (true) {
            str_contains($mountain->trail_type_label, 'Forest') => 'Good shade in many sections',
            str_contains($mountain->trail_type_label, 'Open') => 'Low shade after sunrise',
            default => 'Mixed shade depending on trail section',
        };
        $surfaceLabel = match (true) {
            str_contains($mountain->trail_type_label, 'Rocky') => 'Rocky and uneven footing',
            str_contains($mountain->trail_type_label, 'Grassland') => 'Dry grassland and dusty stretches',
            default => 'Mixed soil trail with occasional loose sections',
        };
        $safetyNote = trim((string) ($mountain->safety_note ?? ''));
        $summary = $safetyNote !== ''
            ? $safetyNote
            : 'Best tackled early with steady pacing, regular hydration, and careful footing on exposed or loose sections.';

        $trailMap = $this->trailProfileService->buildTrailMap($mountain);
        $routeEnd = $this->trailProfileService->routeEnd($mountain->slug);

        return [
            'enabled' => true,
            'subtitle' => $mountain->short_description ?: 'Trail overview, conditions, and field tips for a safer hike day.',
            'distanceKm' => $estimatedDistance,
            'elevationGainM' => (int) $mountain->elevation_meters,
            'routeType' => str_contains(strtolower($mountain->trail_type_label), 'open') ? 'Out & back' : 'Point to point',
            'region' => $mountain->location,
            'gallery' => [
                [
                    'image' => asset($mountain->image_path),
                    'label' => $mountain->jumpoff_name ?: 'Jump-off',
                    'accent' => 'Trail approach',
                ],
                [
                    'image' => asset($mountain->image_path),
                    'label' => $mountain->name.' summit route',
                    'accent' => $mountain->trail_type_label,
                ],
            ],
            'routeMarkers' => [
                ['name' => 'Jump-off', 'detail' => '0.0 km'],
                ['name' => 'Mid trail', 'detail' => number_format($estimatedDistance / 2, 1).' km'],
                ['name' => 'Summit', 'detail' => number_format($estimatedDistance, 1).' km'],
            ],
            'highlights' => [
                'Trail profile: '.$mountain->trail_type_label.' with '.$mountain->difficulty.' pacing.',
                'Meet-up starts at '.$mountain->jumpoff_name.' ('.$mountain->jumpoff_meeting_time.').',
                'Summit day timing works best with early-start hydration and pacing checks.',
            ],
            'topSights' => [
                [
                    'name' => $mountain->jumpoff_name,
                    'type' => 'Trailhead',
                    'description' => $mountain->jumpoff_address,
                ],
                [
                    'name' => $mountain->name.' Summit',
                    'type' => 'Peak',
                    'description' => 'Main summit area with elevation at '.$mountain->elevation_label.'.',
                ],
                [
                    'name' => 'Trail route',
                    'type' => 'Scenic section',
                    'description' => 'Expected terrain: '.$mountain->trail_type_label.'.',
                ],
            ],
            'trailMap' => [...$trailMap],
            'routeEnd' => $routeEnd,
            'conditions' => [
                'crowdLabel' => $crowdLabel,
                'shadeLabel' => $shadeLabel,
                'surfaceLabel' => $surfaceLabel,
                'summary' => $summary,
                'tips' => [
                    'Start 30-60 minutes before the listed meeting time to settle permits and warm up.',
                    'Bring enough water for '.$mountain->duration_label.' and add electrolytes for exposed sections.',
                    'Use short, controlled steps on steep descents and avoid rushing loose ground.',
                ],
            ],
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

        // Block duplicate bookings: same hiker, same date, still active.
        // Cancelled and rejected entries don't count so a hiker can re-book
        // after being declined or after they cancelled themselves.
        $duplicate = HikeBooking::query()
            ->where('user_id', Auth::id())
            ->whereDate('hike_on', $validated['hike_on'])
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->first();
        if ($duplicate) {
            $msg = match ($duplicate->status) {
                'pending' => 'You already have a pending booking on this date. Wait for the guide to respond before booking another.',
                'approved' => 'You already have an approved hike on this date. Cancel it first if you want to switch.',
                'in_progress' => 'You already have a hike in progress today. Finish that one before booking another.',
                default => 'You already have a booking on this date.',
            };
            throw ValidationException::withMessages([
                'hike_on' => [$msg],
            ]);
        }

        $booking = HikeBooking::query()->create([
            'user_id' => Auth::id(),
            'mountain_id' => $mountain->id,
            'tour_guide_id' => $guide->id,
            'hike_on' => $validated['hike_on'],
            'hikers_count' => $validated['hikers_count'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'expected_price' => $this->estimateBookingPrice($mountain, (int) $validated['hikers_count']),
        ]);

        AuditLogger::log('booking.created', "Booked {$mountain->name} on {$validated['hike_on']}", Auth::user(), $booking, [
            'mountain' => $mountain->name,
            'guide_id' => $guide->id,
            'hikers_count' => $validated['hikers_count'],
        ]);

        // Notify the assigned tour guide so they can review the request.
        if ($guide->user_id) {
            $hikerName = Auth::user()->full_name ?? 'A hiker';
            NotificationDispatcher::notify(
                $guide->user_id,
                'booking.created',
                'New booking request',
                "{$hikerName} requested a hike on {$mountain->name} for ".\Carbon\Carbon::parse($validated['hike_on'])->format('M j, Y').'.',
                url('/tour-guide#bookings'),
                'lucide:calendar-plus',
                ['booking_id' => $booking->id]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking request sent! Your guide will review it (pending).',
        ]);
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
        AuditLogger::log('booking.cancelled', "Cancelled booking #{$booking->id}", Auth::user(), $booking);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled.',
        ]);
    }

    public function checkInScan(Request $request, HikeBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $payload = $this->parseBookingQrPayload((string) $request->input('qr_payload', ''));

        if (($payload['action'] ?? null) !== 'checkin') {
            throw ValidationException::withMessages([
                'qr_payload' => 'This QR code is for check-out, not check-in.',
            ]);
        }

        if (($payload['mountain_id'] ?? null) !== null && (int) $payload['mountain_id'] !== (int) $booking->mountain_id) {
            throw ValidationException::withMessages([
                'qr_payload' => 'This QR code is for a different jump-off point.',
            ]);
        }

        if (! $booking->canCheckIn()) {
            // Surface the most likely reason so the hiker isn't left guessing.
            $message = 'This booking cannot be checked in right now.';
            if ($booking->checked_in_at !== null) {
                $message = 'You have already checked in for this hike.';
            } elseif ($booking->status !== 'approved') {
                $message = 'This booking is not approved yet.';
            } elseif (! $booking->isHikeDay()) {
                $hikeDate = $booking->hike_on?->format('M j, Y');
                $message = $booking->hike_on && $booking->hike_on->isFuture()
                    ? "Check-in opens on your hike day ($hikeDate)."
                    : "This hike was scheduled for $hikeDate. Contact your tour guide.";
            }
            throw ValidationException::withMessages([
                'booking' => $message,
            ]);
        }

        DB::transaction(function () use ($booking): void {
            $booking->forceFill([
                'status' => 'in_progress',
                'checked_in_at' => now(),
            ])->save();
        });

        AuditLogger::log('booking.checked_in', "Checked in booking #{$booking->id}", Auth::user(), $booking);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful. Enjoy your hike!',
            'booking' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'checked_in_at' => optional($booking->checked_in_at)->toIso8601String(),
                'checked_out_at' => optional($booking->checked_out_at)->toIso8601String(),
            ],
        ]);
    }

    public function checkOutScan(Request $request, HikeBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $payload = $this->parseBookingQrPayload((string) $request->input('qr_payload', ''));

        if (($payload['action'] ?? null) !== 'checkout') {
            throw ValidationException::withMessages([
                'qr_payload' => 'This QR code is for check-in, not check-out.',
            ]);
        }

        if (($payload['mountain_id'] ?? null) !== null && (int) $payload['mountain_id'] !== (int) $booking->mountain_id) {
            throw ValidationException::withMessages([
                'qr_payload' => 'This QR code is for a different jump-off point.',
            ]);
        }

        if (! $booking->canCheckOut()) {
            throw ValidationException::withMessages([
                'booking' => 'You must check in first before checking out.',
            ]);
        }

        DB::transaction(function () use ($booking): void {
            $booking->forceFill([
                'status' => 'completed',
                'checked_out_at' => now(),
            ])->save();
        });

        AuditLogger::log('booking.checked_out', "Checked out booking #{$booking->id}", Auth::user(), $booking);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful. Hike marked as completed.',
            'booking' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'checked_in_at' => optional($booking->checked_in_at)->toIso8601String(),
                'checked_out_at' => optional($booking->checked_out_at)->toIso8601String(),
            ],
        ]);
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

    /**
     * Toggle the current user's like on a community post.
     * Returns the fresh like count and whether the current user now likes it.
     */
    public function toggleCommunityPostLike(Request $request, CommunityPost $post)
    {
        $user = Auth::user();

        $existing = CommunityPostLike::query()
            ->where('community_post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            CommunityPostLike::query()->create([
                'community_post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => CommunityPostLike::query()->where('community_post_id', $post->id)->count(),
        ]);
    }

    /**
     * List comments on a community post (oldest first so replies read top-down).
     */
    public function indexCommunityPostComments(CommunityPost $post)
    {
        $comments = CommunityPostComment::query()
            ->where('community_post_id', $post->id)
            ->with(array_filter([
                User::supportsDatabaseProfilePictures() ? 'user.profilePicture' : 'user',
            ]))
            ->orderBy('created_at')
            ->get()
            ->map(function (CommunityPostComment $comment) {
                $author = $comment->user;
                $first = (string) ($author?->first_name ?? '');
                $last = (string) ($author?->last_name ?? '');
                $initials = strtoupper(
                    ($first !== '' ? substr($first, 0, 1) : '').
                    ($last !== '' ? substr($last, 0, 1) : '')
                );
                if ($initials === '') {
                    $initials = 'HC';
                }

                return [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'created_at_human' => optional($comment->created_at)->diffForHumans(),
                    'author_name' => trim($first.' '.$last) ?: ($author?->email ?? 'Hiker'),
                    'author_initials' => $initials,
                    'author_photo' => $author?->profile_picture_url,
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    /**
     * Add a comment to a community post.
     */
    public function storeCommunityPostComment(Request $request, CommunityPost $post)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        $user = Auth::user();

        $comment = CommunityPostComment::query()->create([
            'community_post_id' => $post->id,
            'user_id' => $user->id,
            'body' => $validated['body'],
        ]);

        $first = (string) ($user->first_name ?? '');
        $last = (string) ($user->last_name ?? '');
        $initials = strtoupper(
            ($first !== '' ? substr($first, 0, 1) : '').
            ($last !== '' ? substr($last, 0, 1) : '')
        );
        if ($initials === '') {
            $initials = 'HC';
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'created_at_human' => $comment->created_at->diffForHumans(),
                'author_name' => trim($first.' '.$last) ?: $user->email,
                'author_initials' => $initials,
                'author_photo' => $user->profile_picture_url,
            ],
            'comments_count' => CommunityPostComment::query()->where('community_post_id', $post->id)->count(),
        ]);
    }

    public function updateProfilePicture(Request $request, ProfilePictureDatabaseWriter $writer)
    {
        $request->validate([
            // max is kilobytes; phone camera JPEGs are often > 2MB
            'profile_picture' => ['required', 'image', 'max:10240', 'mimes:jpeg,png,gif,webp'],
        ]);

        $user = Auth::user();

        try {
            $writer->storeFromUploadedFile($user, $request->file('profile_picture'));
        } catch (\Throwable $e) {
            Log::error('Hiker profile picture upload failed', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Could not save your photo.',
            ], 500);
        }

        $user->refresh();

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

        AuditLogger::log('hiker.profile_updated', 'Updated profile details', $user);

        return response()->json([
            'success' => true,
            'full_name' => $user->full_name,
        ]);
    }

    public function recordLocation(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'accuracy_m' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'altitude_m' => ['nullable', 'numeric'],
            'speed_mps' => ['nullable', 'numeric', 'min:0'],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
            'hike_booking_id' => ['nullable', 'integer', 'exists:hike_bookings,id'],
        ]);

        $userId = Auth::id();
        $bookingId = $validated['hike_booking_id'] ?? null;
        if ($bookingId) {
            $owns = HikeBooking::where('id', $bookingId)->where('user_id', $userId)->exists();
            if (! $owns) {
                $bookingId = null;
            }
        }

        HikerLocation::create([
            'user_id' => $userId,
            'hike_booking_id' => $bookingId,
            'mountain_id' => $validated['mountain_id'] ?? null,
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'accuracy_m' => $validated['accuracy_m'] ?? null,
            'altitude_m' => $validated['altitude_m'] ?? null,
            'speed_mps' => $validated['speed_mps'] ?? null,
            'recorded_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function triggerSos(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'accuracy_m' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
            'hike_booking_id' => ['nullable', 'integer', 'exists:hike_bookings,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $booking = $this->resolveSosBooking($user->id, $validated['hike_booking_id'] ?? null);
        $mountainId = $booking?->mountain_id ?? ($validated['mountain_id'] ?? null);
        $tourGuideId = $booking?->tour_guide_id;

        $alert = SosAlert::create([
            'user_id' => $user->id,
            'hike_booking_id' => $booking?->id,
            'mountain_id' => $mountainId,
            'tour_guide_id' => $tourGuideId,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'accuracy_m' => $validated['accuracy_m'] ?? null,
            'status' => SosAlert::STATUS_OPEN,
            'message' => trim((string) ($validated['message'] ?? '')) ?: 'Emergency SOS triggered from hiker live tracking.',
        ]);

        $alert->load(['user', 'hikeBooking.mountain', 'mountain', 'tourGuide.user']);

        $emailSent = $this->notifySosRecipients($alert);

        AuditLogger::log(
            'hiker.sos_triggered',
            'Emergency SOS triggered by '.$user->full_name,
            $user,
            $alert,
            ['sos_alert_id' => $alert->id, 'email_sent' => $emailSent]
        );

        // In-app notifications: every admin and (if assigned) the tour guide.
        $hikerName = $user->full_name;
        $mountainName = $alert->mountain?->name ?? $alert->hikeBooking?->mountain?->name ?? 'an unspecified mountain';
        $sosBody = "{$hikerName} triggered SOS on {$mountainName}. Open the live map to coordinate response.";
        NotificationDispatcher::notifyAdmins(
            'sos.triggered',
            'SOS triggered',
            $sosBody,
            url('/admin#live-map'),
            'lucide:siren',
            ['sos_alert_id' => $alert->id]
        );
        if ($alert->tourGuide?->user_id) {
            NotificationDispatcher::notify(
                $alert->tourGuide->user_id,
                'sos.triggered',
                'SOS from your hiker',
                $sosBody,
                url('/tour-guide#sos-alerts'),
                'lucide:siren',
                ['sos_alert_id' => $alert->id]
            );
        }

        return response()->json([
            'success' => true,
            'message' => $emailSent
                ? 'SOS sent to Admin and your Tour Guide. Stay where you are if it is safe.'
                : 'SOS recorded. Email notification could not be sent, but Admin and your Tour Guide can see the alert.',
            'email_sent' => $emailSent,
            'alert' => [
                'id' => $alert->id,
                'status' => $alert->status,
                'created_at' => $alert->created_at?->toIso8601String(),
            ],
        ], 201);
    }

    public function storeExperienceFeedback(Request $request)
    {
        $validated = $request->validate([
            'score' => ['required', 'in:bad,okay,great'],
            'dont_show_again' => ['nullable', 'boolean'],
            'context' => ['nullable', 'string', 'max:64'],
        ]);

        $user = Auth::user();

        UserExperienceFeedback::create([
            'user_id' => $user->id,
            'score' => $validated['score'],
            'dont_show_again' => (bool) ($validated['dont_show_again'] ?? false),
            'context' => $validated['context'] ?? 'hiker_dashboard_login',
        ]);

        return response()->json(['success' => true]);
    }

    private function resolveSosBooking(int $userId, ?int $requestedBookingId): ?HikeBooking
    {
        if ($requestedBookingId) {
            $booking = HikeBooking::query()
                ->where('user_id', $userId)
                ->with(array_filter([
                    'mountain',
                    'tourGuide.user:id,first_name,last_name,email,phone,profile_picture_path',
                    User::supportsDatabaseProfilePictures() ? 'tourGuide.user.profilePicture:user_id,mime,updated_at' : null,
                ]))
                ->find($requestedBookingId);

            if ($booking) {
                return $booking;
            }
        }

        return HikeBooking::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->whereDate('hike_on', '>=', today())
            ->with(array_filter([
                'mountain',
                'tourGuide.user:id,first_name,last_name,email,phone,profile_picture_path',
                User::supportsDatabaseProfilePictures() ? 'tourGuide.user.profilePicture:user_id,mime,updated_at' : null,
            ]))
            ->orderBy('hike_on')
            ->orderBy('id')
            ->first();
    }

    /**
     * @return array{version?: int, mountain_id?: int|null, action: string}
     */
    private function parseBookingQrPayload(string $rawPayload): array
    {
        $payload = trim($rawPayload);
        if ($payload === '') {
            throw ValidationException::withMessages([
                'qr_payload' => 'QR data is required.',
            ]);
        }

        $decoded = json_decode($payload, true);
        if (! is_array($decoded)) {
            $decoded = json_decode(base64_decode($payload, true) ?: '', true);
        }
        if (! is_array($decoded)) {
            $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/'), true) ?: '', true);
        }
        if (! is_array($decoded)) {
            $queryData = [];
            $queryString = '';
            if (preg_match('/^https?:\/\//i', $payload) === 1) {
                $queryString = (string) parse_url($payload, PHP_URL_QUERY);
            } elseif (str_contains($payload, '=')) {
                $queryString = $payload;
            }
            if ($queryString !== '') {
                parse_str($queryString, $queryData);
                if (is_array($queryData) && $queryData !== []) {
                    $decoded = $queryData;
                }
            }
        }

        if (! is_array($decoded)) {
            throw ValidationException::withMessages([
                'qr_payload' => 'Invalid QR format. Use JSON, base64 JSON, or URL/query format.',
            ]);
        }

        $mountainRaw = $decoded['mountain_id'] ?? $decoded['mountainId'] ?? null;
        $actionRaw = $decoded['action'] ?? $decoded['type'] ?? null;
        $mountainId = is_numeric($mountainRaw) ? (int) $mountainRaw : null;
        $action = strtolower(str_replace(['-', '_', ' '], '', (string) $actionRaw));
        if ($action === 'checkinqr') {
            $action = 'checkin';
        } elseif ($action === 'checkoutqr') {
            $action = 'checkout';
        }

        if (! in_array($action, ['checkin', 'checkout'], true)) {
            throw ValidationException::withMessages([
                'qr_payload' => 'QR code action must be checkin or checkout.',
            ]);
        }

        return [
            'version' => isset($decoded['version']) ? (int) $decoded['version'] : 1,
            'mountain_id' => $mountainId,
            'action' => $action,
        ];
    }

    private function notifySosRecipients(SosAlert $alert): bool
    {
        $recipients = collect();
        $guideUser = $alert->tourGuide?->user;
        if ($guideUser?->email) {
            $recipients->push($guideUser);
        }

        User::query()
            ->where('role', User::ROLE_ADMIN)
            ->get()
            ->each(fn (User $admin) => $recipients->push($admin));

        $sentAny = false;
        foreach ($recipients->unique('email') as $recipient) {
            $sentAny = $this->emailService->sendSosAlert($recipient, $alert) || $sentAny;
        }

        return $sentAny;
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

        AuditLogger::log('hiker.password_changed', 'Changed account password', $user);

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
            ->with(array_filter([
                'mountain',
                'tourGuide',
                'mountainReview',
                'tourGuide.user:id,first_name,last_name,email,phone,profile_picture_path',
                User::supportsDatabaseProfilePictures() ? 'tourGuide.user.profilePicture:user_id,mime,updated_at' : null,
            ]))
            ->firstOrFail();

        if ($booking->status !== 'completed') {
            throw ValidationException::withMessages([
                'booking_id' => 'Only completed hikes can receive feedback.',
            ]);
        }

        return $booking;
    }

    private function estimateBookingPrice(Mountain $mountain, int $hikersCount): float
    {
        $headCount = max(1, $hikersCount);
        $perPersonTotal = (float) (
            ($mountain->registration_fee_per_person ?? 0)
            + ($mountain->environmental_fee_per_person ?? 0)
            + ($mountain->local_fee_per_person ?? 0)
            + ($mountain->guide_fee_per_person ?? 0)
        );

        return (float) (($perPersonTotal * $headCount) + (float) ($mountain->guide_fee_per_group ?? 0));
    }
}
