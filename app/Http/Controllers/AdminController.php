<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\HikeBooking;
use App\Models\HikerLocation;
use App\Models\Mountain;
use App\Models\MountainReview;
use App\Models\TourGuide;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\TrailDataService;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct(protected TrailDataService $trailDataService)
    {
    }

    /* ==========================================================
     * Dashboard / Analytics / System Health
     * ========================================================== */

    public function index(Request $request)
    {
        $user = Auth::user();

        $totalHikers = User::where('role', User::ROLE_HIKER)->count();
        $totalGuides = User::where('role', User::ROLE_TOUR_GUIDE)->count();
        $totalAdmins = User::where('role', User::ROLE_ADMIN)->count();
        $totalMountains = Mountain::count();
        $totalBookings = HikeBooking::count();
        $bookingsByStatus = HikeBooking::query()
            ->select('status', DB::raw('COUNT(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $reviewsCount = MountainReview::count();
        $avgMountainRating = round((float) MountainReview::avg('rating'), 2);

        $signupsLast30 = User::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')->orderBy('d')
            ->get()
            ->map(fn ($r) => ['date' => (string) $r->d, 'count' => (int) $r->c])
            ->values();

        $bookingsLast30 = HikeBooking::query()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')->orderBy('d')
            ->get()
            ->map(fn ($r) => ['date' => (string) $r->d, 'count' => (int) $r->c])
            ->values();

        $topMountains = Mountain::query()
            ->withCount(['hikeBookings as bookings_count'])
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get(['id', 'name', 'location']);

        $topGuides = TourGuide::query()
            ->withCount(['hikeBookings as bookings_count'])
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        $recentLogs = AuditLog::query()
            ->with(['user:id,first_name,last_name,email', 'actor:id,first_name,last_name,email'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $mountains = Mountain::orderBy('sort_order')->get();
        $guides = TourGuide::with(['user:id,email', 'mountain:id,name'])->orderBy('sort_order')->get();
        $admins = User::where('role', User::ROLE_ADMIN)->orderBy('first_name')->get();
        $hikers = User::where('role', User::ROLE_HIKER)
            ->withCount('hikeBookings')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $mountainMaps = $this->buildMountainMaps();
        $liveHikerCount = collect($mountainMaps)->sum(fn ($m) => count($m['hikers']));

        $auditLogs = AuditLog::query()
            ->with(['user:id,first_name,last_name,email,role', 'actor:id,first_name,last_name,email,role'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $health = $this->collectSystemHealth();

        $stats = [
            'total_hikers' => $totalHikers,
            'total_guides' => $totalGuides,
            'total_admins' => $totalAdmins,
            'total_mountains' => $totalMountains,
            'total_bookings' => $totalBookings,
            'pending_bookings' => (int) ($bookingsByStatus['pending'] ?? 0),
            'approved_bookings' => (int) ($bookingsByStatus['approved'] ?? 0),
            'completed_bookings' => (int) ($bookingsByStatus['completed'] ?? 0),
            'cancelled_bookings' => (int) (($bookingsByStatus['cancelled'] ?? 0) + ($bookingsByStatus['rejected'] ?? 0)),
            'reviews_count' => $reviewsCount,
            'avg_mountain_rating' => $avgMountainRating,
            'live_hikers' => $liveHikerCount,
        ];

        $analytics = [
            'signups_30d' => $signupsLast30,
            'bookings_30d' => $bookingsLast30,
            'top_mountains' => $topMountains,
            'top_guides' => $topGuides,
        ];

        return view('admin', compact(
            'user', 'stats', 'analytics', 'recentLogs',
            'mountains', 'guides', 'admins', 'hikers',
            'mountainMaps', 'auditLogs', 'health'
        ));
    }

    private function collectSystemHealth(): array
    {
        $dbOk = true;
        $dbError = null;
        try {
            DB::connection()->getPdo();
            DB::select('SELECT 1');
        } catch (\Throwable $e) {
            $dbOk = false;
            $dbError = $e->getMessage();
        }

        $tables = ['users', 'tour_guides', 'mountains', 'hike_bookings', 'mountain_reviews', 'community_posts', 'audit_logs', 'hiker_locations'];
        $tableCounts = [];
        foreach ($tables as $t) {
            try {
                $tableCounts[$t] = Schema::hasTable($t) ? (int) DB::table($t)->count() : null;
            } catch (\Throwable $e) {
                $tableCounts[$t] = null;
            }
        }

        $diskFree = @disk_free_space(base_path());
        $diskTotal = @disk_total_space(base_path());

        $storagePath = storage_path();
        $storageWritable = is_writable($storagePath);

        $logFile = storage_path('logs/laravel.log');
        $logSize = file_exists($logFile) ? filesize($logFile) : 0;

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug' => (bool) config('app.debug'),
            'timezone' => config('app.timezone'),
            'database' => [
                'driver' => config('database.default'),
                'connection_ok' => $dbOk,
                'error' => $dbError,
                'database_name' => DB::connection()->getDatabaseName(),
                'tables' => $tableCounts,
            ],
            'storage' => [
                'writable' => $storageWritable,
                'path' => $storagePath,
                'log_size_kb' => (int) round($logSize / 1024),
            ],
            'disk' => [
                'free_gb' => $diskFree ? round($diskFree / 1024 / 1024 / 1024, 2) : null,
                'total_gb' => $diskTotal ? round($diskTotal / 1024 / 1024 / 1024, 2) : null,
                'used_pct' => ($diskFree && $diskTotal) ? round(100 - ($diskFree / $diskTotal * 100), 1) : null,
            ],
        ];
    }

    /* ==========================================================
     * Tour Guide CRUD (admin manages everything except bio + photo)
     * ========================================================== */

    public function storeTourGuide(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,22}$/'],
            'password' => ['required', 'string', 'min:8'],
            'specialty' => ['required', 'string', 'max:128'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
            'status' => ['required', 'in:available,on-hike,unavailable,off-duty'],
        ], [
            'phone.regex' => 'Enter a valid phone number.',
        ]);

        $guide = DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role' => User::ROLE_TOUR_GUIDE,
                'email_verified_at' => now(),
            ]);

            $slug = $this->uniqueGuideSlug($data['first_name'].' '.$data['last_name'], $user->id);

            return TourGuide::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'specialty' => $data['specialty'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'experience_years' => (int) $data['experience_years'],
                'mountain_id' => $data['mountain_id'] ?? null,
                'status' => $data['status'],
                'avatar_gradient' => 'linear-gradient(135deg,#065f46,#10b981)',
                'sort_order' => 99,
            ]);
        });

        AuditLogger::log('admin.guide.created', "Admin created tour guide {$guide->email}", $guide->user, $guide, [
            'guide_id' => $guide->id,
        ]);

        return back()->with('admin_status', 'Tour guide created successfully.');
    }

    public function updateTourGuide(Request $request, TourGuide $tourGuide)
    {
        $tourGuide->load('user');
        $userId = $tourGuide->user_id;

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,22}$/'],
            'specialty' => ['required', 'string', 'max:128'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'mountain_id' => ['nullable', 'integer', 'exists:mountains,id'],
            'status' => ['required', 'in:available,on-hike,unavailable,off-duty'],
            'reset_password' => ['nullable', 'string', 'min:8'],
        ], [
            'phone.regex' => 'Enter a valid phone number.',
        ]);

        DB::transaction(function () use ($data, $tourGuide) {
            if ($tourGuide->user) {
                $tourGuide->user->fill([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ]);
                if (! empty($data['reset_password'])) {
                    $tourGuide->user->password = Hash::make($data['reset_password']);
                }
                $tourGuide->user->save();
            }

            $tourGuide->fill([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'specialty' => $data['specialty'],
                'experience_years' => (int) $data['experience_years'],
                'mountain_id' => $data['mountain_id'] ?? null,
                'status' => $data['status'],
            ])->save();
        });

        AuditLogger::log('admin.guide.updated', "Admin updated tour guide {$tourGuide->email}", $tourGuide->user, $tourGuide, [
            'reset_password' => ! empty($data['reset_password']),
        ]);

        return back()->with('admin_status', 'Tour guide updated.');
    }

    public function destroyTourGuide(TourGuide $tourGuide)
    {
        $email = $tourGuide->email;
        $userId = $tourGuide->user_id;

        DB::transaction(function () use ($tourGuide) {
            if ($tourGuide->user) {
                $tourGuide->user->delete();
            }
            $tourGuide->delete();
        });

        AuditLogger::log('admin.guide.deleted', "Admin deleted tour guide {$email}", null, null, [
            'guide_id' => $tourGuide->id,
            'user_id' => $userId,
        ]);

        return back()->with('admin_status', 'Tour guide removed.');
    }

    private function uniqueGuideSlug(string $name, int $userId): string
    {
        $base = Str::slug($name) ?: 'guide-'.$userId;
        $slug = $base;
        $i = 2;
        while (TourGuide::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }
        return $slug;
    }

    /* ==========================================================
     * Admin account CRUD
     * ========================================================== */

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{7,22}$/'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'phone.regex' => 'Enter a valid phone number.',
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        AuditLogger::log('admin.admin.created', "Admin created new admin {$user->email}", $user, $user);

        return back()->with('admin_status', 'New admin created.');
    }

    public function destroyAdmin(User $admin)
    {
        if (! $admin->isAdmin()) {
            abort(404);
        }
        if ($admin->id === Auth::id()) {
            return back()->withErrors(['admin' => 'You cannot delete your own admin account.']);
        }
        $remaining = User::where('role', User::ROLE_ADMIN)->where('id', '!=', $admin->id)->count();
        if ($remaining < 1) {
            return back()->withErrors(['admin' => 'At least one admin account must remain.']);
        }

        $email = $admin->email;
        $admin->delete();

        AuditLogger::log('admin.admin.deleted', "Admin deleted admin account {$email}");

        return back()->with('admin_status', 'Admin account removed.');
    }

    /* ==========================================================
     * Hiker management
     * ========================================================== */

    public function showHiker(User $hiker)
    {
        if (! $hiker->isHiker()) {
            abort(404);
        }

        $hiker->load(['hikeBookings.mountain', 'hikeBookings.tourGuide']);

        $logs = AuditLog::query()
            ->with('actor:id,first_name,last_name,email,role')
            ->where('user_id', $hiker->id)
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $recentLocations = HikerLocation::query()
            ->with('mountain:id,name')
            ->where('user_id', $hiker->id)
            ->orderByDesc('recorded_at')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'hiker' => [
                'id' => $hiker->id,
                'full_name' => $hiker->full_name,
                'email' => $hiker->email,
                'phone' => $hiker->phone,
                'avatar' => $hiker->profile_picture_url,
                'created_at' => $hiker->created_at?->toIso8601String(),
                'bookings' => $hiker->hikeBookings->map(fn ($b) => [
                    'id' => $b->id,
                    'mountain' => $b->mountain?->name,
                    'guide' => $b->tourGuide?->full_name,
                    'date' => $b->hike_on?->toDateString(),
                    'status' => $b->status,
                    'hikers_count' => $b->hikers_count,
                ])->values(),
            ],
            'logs' => $logs->map(fn ($l) => [
                'id' => $l->id,
                'action' => $l->action,
                'description' => $l->description,
                'actor' => $l->actor?->full_name,
                'created_at' => $l->created_at?->toIso8601String(),
                'meta' => $l->meta,
            ])->values(),
            'locations' => $recentLocations->map(fn ($p) => [
                'lat' => (float) $p->lat,
                'lng' => (float) $p->lng,
                'mountain' => $p->mountain?->name,
                'recorded_at' => $p->recorded_at?->toIso8601String(),
                'accuracy_m' => $p->accuracy_m,
            ])->values(),
        ]);
    }

    public function suspendHiker(User $hiker)
    {
        if (! $hiker->isHiker()) abort(404);
        $hiker->delete();
        AuditLogger::log('admin.hiker.deleted', "Admin removed hiker {$hiker->email}", $hiker);
        return back()->with('admin_status', 'Hiker account removed.');
    }

    /* ==========================================================
     * Live map polling endpoint (per-mountain)
     * ========================================================== */

    public function liveLocations()
    {
        return response()->json([
            'success' => true,
            'mountains' => $this->buildMountainMaps(),
            'fetched_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Build the per-mountain live-map payload.
     *
     * For every mountain we return:
     *   - identification + jumpoff/summit coordinates
     *   - the live trail polyline (via TrailDataService, fallback to jumpoff→summit)
     *   - every active hiker assigned to that mountain (any approved booking
     *     scheduled today). For each hiker we plot:
     *       * GPS source — most recent location ping in the last 30 minutes
     *       * Simulated source (TrailDataService dataset) — when no recent ping
     *         exists, we estimate position by interpolating along the trail
     *         based on hours elapsed since the conventional 5 AM start time.
     *
     * @return array<int, array{
     *   id:int, slug:string, name:string,
     *   jumpoff:array{lat:float,lng:float}, summit:array{lat:float,lng:float},
     *   trail:array<int,array{lat:float,lng:float}>, trail_source:string, trail_label:string,
     *   hikers:array<int,array<string,mixed>>
     * }>
     */
    private function buildMountainMaps(): array
    {
        $mountains = Mountain::query()->orderBy('sort_order')->get();
        $today = today();
        $pingCutoff = now()->subMinutes(30);

        $activeBookings = HikeBooking::query()
            ->with(['user:id,first_name,last_name,email,profile_picture_path,phone'])
            ->whereDate('hike_on', $today)
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->groupBy('mountain_id');

        $userIds = $activeBookings->flatten()->pluck('user_id')->filter()->unique()->all();
        $latestPings = $userIds
            ? HikerLocation::query()
                ->whereIn('user_id', $userIds)
                ->where('recorded_at', '>=', $pingCutoff)
                ->orderByDesc('recorded_at')
                ->get()
                ->groupBy('user_id')
                ->map(fn ($pings) => $pings->first())
            : collect();

        $maps = [];

        foreach ($mountains as $mountain) {
            $trailMap = $this->trailDataService->buildTrailMap(
                $mountain,
                $mountain->name.' Trail',
                is_array($mountain->trail_plan) ? [] : []
            );
            $trail = $trailMap['path'];

            $bookings = $activeBookings->get($mountain->id, collect());

            $hikers = $bookings->map(function (HikeBooking $b) use ($latestPings, $trail) {
                $user = $b->user;
                if (! $user) return null;

                $ping = $latestPings->get($user->id);
                $startedAt = $b->hike_on?->copy()->setTime(5, 0);

                if ($ping) {
                    return $this->hikerMarker($user, $b, [
                        'source' => 'gps',
                        'lat' => (float) $ping->lat,
                        'lng' => (float) $ping->lng,
                        'recorded_at' => $ping->recorded_at?->toIso8601String(),
                        'accuracy_m' => $ping->accuracy_m,
                        'altitude_m' => $ping->altitude_m,
                        'speed_mps' => $ping->speed_mps,
                        'started_at' => $startedAt?->toIso8601String(),
                    ]);
                }

                $progress = $this->estimateProgressPct($startedAt);
                $sim = $this->positionAlongTrail($trail, $progress);
                if (! $sim) return null;

                return $this->hikerMarker($user, $b, [
                    'source' => 'simulated',
                    'lat' => $sim['lat'],
                    'lng' => $sim['lng'],
                    'progress_pct' => round($progress * 100),
                    'started_at' => $startedAt?->toIso8601String(),
                    'note' => 'No recent GPS — position estimated from trail dataset',
                ]);
            })->filter()->values()->all();

            $maps[] = [
                'id' => $mountain->id,
                'slug' => $mountain->slug,
                'name' => $mountain->name,
                'location' => $mountain->location,
                'difficulty' => $mountain->difficulty,
                'jumpoff' => [
                    'lat' => (float) $mountain->jumpoff_lat,
                    'lng' => (float) $mountain->jumpoff_lng,
                    'label' => $mountain->jumpoff_name ?: 'Jump-off',
                ],
                'summit' => [
                    'lat' => (float) $mountain->summit_lat,
                    'lng' => (float) $mountain->summit_lng,
                    'label' => $mountain->name.' Summit',
                ],
                'trail' => $trail,
                'trail_source' => $trailMap['source'],
                'trail_label' => $trailMap['sourceLabel'],
                'hikers' => $hikers,
                'active_count' => count($hikers),
                'gps_count' => collect($hikers)->where('source', 'gps')->count(),
                'simulated_count' => collect($hikers)->where('source', 'simulated')->count(),
            ];
        }

        return $maps;
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private function hikerMarker(User $user, HikeBooking $booking, array $extra): array
    {
        return array_merge([
            'user_id' => $user->id,
            'name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->profile_picture_url,
            'initials' => strtoupper(substr($user->first_name, 0, 1).substr($user->last_name, 0, 1)),
            'booking_id' => $booking->id,
            'hike_date' => $booking->hike_on?->toDateString(),
            'hikers_count' => $booking->hikers_count,
            'booking_status' => $booking->status,
        ], $extra);
    }

    /**
     * Estimate hike completion percentage (0..1) based on elapsed time
     * since the conventional 5 AM start, capped to [0, 0.95].
     */
    private function estimateProgressPct(?CarbonInterface $startedAt): float
    {
        if (! $startedAt) return 0.0;

        $now = Carbon::now();
        if ($now->lt($startedAt)) {
            return 0.0;
        }

        $elapsedHours = $startedAt->diffInMinutes($now) / 60.0;
        $estimatedHours = 5.0; // typical day-hike duration
        $pct = $elapsedHours / $estimatedHours;

        return max(0.02, min(0.95, $pct));
    }

    /**
     * Pick a point along a polyline at fractional progress (0..1) by
     * walking the trail until we've covered that fraction of total length.
     *
     * @param  array<int, array{lat: float, lng: float}>  $trail
     * @return array{lat: float, lng: float}|null
     */
    private function positionAlongTrail(array $trail, float $progress): ?array
    {
        $count = count($trail);
        if ($count === 0) return null;
        if ($count === 1) return ['lat' => (float) $trail[0]['lat'], 'lng' => (float) $trail[0]['lng']];

        $progress = max(0.0, min(1.0, $progress));

        $segmentLengths = [];
        $total = 0.0;
        for ($i = 1; $i < $count; $i++) {
            $d = $this->haversineKm($trail[$i - 1], $trail[$i]);
            $segmentLengths[] = $d;
            $total += $d;
        }
        if ($total <= 0) return ['lat' => (float) $trail[0]['lat'], 'lng' => (float) $trail[0]['lng']];

        $target = $total * $progress;
        $covered = 0.0;
        for ($i = 0; $i < count($segmentLengths); $i++) {
            $segLen = $segmentLengths[$i];
            if ($covered + $segLen >= $target) {
                $remain = $target - $covered;
                $frac = $segLen > 0 ? ($remain / $segLen) : 0.0;
                $a = $trail[$i];
                $b = $trail[$i + 1];
                return [
                    'lat' => (float) ($a['lat'] + ($b['lat'] - $a['lat']) * $frac),
                    'lng' => (float) ($a['lng'] + ($b['lng'] - $a['lng']) * $frac),
                ];
            }
            $covered += $segLen;
        }

        $last = $trail[$count - 1];
        return ['lat' => (float) $last['lat'], 'lng' => (float) $last['lng']];
    }

    /**
     * @param  array{lat: float|int|string, lng: float|int|string}  $a
     * @param  array{lat: float|int|string, lng: float|int|string}  $b
     */
    private function haversineKm(array $a, array $b): float
    {
        $r = 6371.0;
        $lat1 = deg2rad((float) $a['lat']);
        $lat2 = deg2rad((float) $b['lat']);
        $dLat = $lat2 - $lat1;
        $dLng = deg2rad((float) $b['lng'] - (float) $a['lng']);
        $h = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;
        return $r * 2 * atan2(sqrt($h), sqrt(1 - $h));
    }

    /* ==========================================================
     * Audit log search
     * ========================================================== */

    public function auditLogs(Request $request)
    {
        $q = AuditLog::query()
            ->with(['user:id,first_name,last_name,email,role', 'actor:id,first_name,last_name,email,role'])
            ->orderByDesc('id');

        if ($action = $request->query('action')) {
            $q->where('action', 'like', '%'.$action.'%');
        }
        if ($userId = $request->query('user_id')) {
            $q->where('user_id', $userId);
        }

        $logs = $q->limit(300)->get();

        return response()->json([
            'success' => true,
            'logs' => $logs->map(fn ($l) => [
                'id' => $l->id,
                'action' => $l->action,
                'description' => $l->description,
                'user' => $l->user?->full_name,
                'user_email' => $l->user?->email,
                'actor' => $l->actor?->full_name,
                'actor_email' => $l->actor?->email,
                'meta' => $l->meta,
                'ip_address' => $l->ip_address,
                'created_at' => $l->created_at?->toIso8601String(),
            ])->values(),
        ]);
    }
}
