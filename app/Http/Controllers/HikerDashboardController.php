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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class HikerDashboardController extends Controller
{
    public function __construct(protected EmailService $emailService) {}

    public function index()
    {
        $user = Auth::user();
        $mountains = Mountain::query()->orderBy('sort_order')->get();
        $guides = TourGuide::query()->with('mountain')->orderBy('sort_order')->get();
        $bookings = HikeBooking::query()
            ->where('user_id', $user->id)
            ->with(['mountain', 'tourGuide'])
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
        $mountainReviews = MountainReview::query()->with('mountain')->latest()->limit(15)->get();
        $packingItems = PackingItem::query()->orderBy('category')->orderBy('sort_order')->get();

        $trailMountain = $upcoming?->mountain ?? $mountains->first();
        $focusMountainForWeather = $upcoming?->mountain ?? $mountains->first();
        $weatherLat = $focusMountainForWeather ? $focusMountainForWeather->meteoLat() : null;
        $weatherLng = $focusMountainForWeather ? $focusMountainForWeather->meteoLng() : null;

        $mountainData = $this->mountainsJsPayload($mountains);
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
            'mountainReviews',
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
     * @return array<string, array<string, mixed>>
     */
    private function mountainsJsPayload($mountains): array
    {
        $out = [];
        foreach ($mountains as $m) {
            $out[$m->slug] = [
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
                'gear' => $m->gear ?? [],
                'emergencyContact' => $m->emergency_contact,
            ];
        }

        return $out;
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
            'mountain' => ['required', 'string', 'exists:mountains,slug'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        $mountain = Mountain::query()->where('slug', $validated['mountain'])->firstOrFail();

        MountainReview::query()->create([
            'user_id' => Auth::id(),
            'reviewer_name' => Auth::user()->full_name,
            'rating' => $validated['rating'],
            'body' => $validated['body'] ?? '',
            'mountain_id' => $mountain->id,
        ]);

        return response()->json(['success' => true]);
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
}
