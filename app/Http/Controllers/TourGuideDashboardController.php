<?php

namespace App\Http\Controllers;

use App\Models\HikeBooking;
use App\Models\Mountain;
use App\Models\SosAlert;
use App\Models\TourGuide;
use App\Services\AuditLogger;
use App\Services\ProfilePictureDatabaseWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TourGuideDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guide = $this->resolveGuide($user);

        $mountains = Mountain::query()->orderBy('sort_order')->get();

        $bookings = HikeBooking::query()
            ->where('tour_guide_id', $guide->id)
            ->with([
                'user:id,first_name,last_name,email,phone,profile_picture_path',
                'user.profilePicture:user_id,mime',
                'mountain',
                'mountainReview',
            ])
            ->orderByDesc('hike_on')
            ->orderByDesc('id')
            ->get();

        $now = today();

        $pending = $bookings->where('status', 'pending')->values();
        $approved = $bookings->where('status', 'approved')->values();
        $completed = $bookings->where('status', 'completed')->values();
        $cancelled = $bookings->whereIn('status', ['cancelled', 'rejected'])->values();

        $upcoming = $bookings
            ->whereIn('status', ['pending', 'approved'])
            ->filter(fn (HikeBooking $b) => $b->hike_on->gte($now))
            ->sortBy('hike_on')
            ->values()
            ->first();

        $hikersServed = $completed->pluck('user_id')->filter()->unique()->count();
        $totalHikers = (int) $completed->sum('hikers_count');
        $ratings = $completed
            ->filter(fn (HikeBooking $b) => $b->rating !== null)
            ->pluck('rating');
        $avgRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : null;

        $stats = [
            'total_bookings' => $bookings->count(),
            'pending' => $pending->count(),
            'completed' => $completed->count(),
            'unique_hikers' => $hikersServed,
            'total_hikers_guided' => $totalHikers,
            'rating' => $avgRating,
            'rating_count' => $ratings->count(),
        ];

        $guideReviews = $completed
            ->filter(fn (HikeBooking $b) => $b->rating !== null)
            ->sortByDesc('updated_at')
            ->take(20)
            ->values();

        $sosAlerts = SosAlert::query()
            ->where('tour_guide_id', $guide->id)
            ->with([
                'user:id,first_name,last_name,email,phone,profile_picture_path',
                'user.profilePicture:user_id,mime',
                'mountain:id,name,location,emergency_contact',
                'hikeBooking:id,hike_on,status,hikers_count,mountain_id',
                'hikeBooking.mountain:id,name,location',
                'acknowledgedBy:id,first_name,last_name',
                'resolvedBy:id,first_name,last_name',
            ])
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 WHEN status = 'acknowledged' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('tour-guide', compact(
            'user',
            'guide',
            'mountains',
            'bookings',
            'pending',
            'approved',
            'completed',
            'cancelled',
            'upcoming',
            'stats',
            'guideReviews',
            'sosAlerts',
        ));
    }

    public function approveBooking(HikeBooking $booking)
    {
        $guide = $this->resolveGuide(Auth::user());
        $this->authorizeOwnership($booking, $guide);

        if ($booking->status !== 'pending') {
            throw ValidationException::withMessages([
                'booking' => 'Only pending bookings can be approved.',
            ]);
        }

        $booking->update(['status' => 'approved']);
        AuditLogger::log('booking.approved', "Approved booking #{$booking->id}", $booking->user, $booking, [
            'mountain_id' => $booking->mountain_id,
        ]);

        return response()->json(['success' => true, 'status' => $booking->status]);
    }

    public function rejectBooking(HikeBooking $booking)
    {
        $guide = $this->resolveGuide(Auth::user());
        $this->authorizeOwnership($booking, $guide);

        if (! in_array($booking->status, ['pending', 'approved'], true)) {
            throw ValidationException::withMessages([
                'booking' => 'This booking cannot be rejected anymore.',
            ]);
        }

        $booking->update(['status' => 'cancelled']);
        AuditLogger::log('booking.rejected', "Rejected/cancelled booking #{$booking->id}", $booking->user, $booking);

        return response()->json(['success' => true, 'status' => $booking->status]);
    }

    public function completeBooking(Request $request, HikeBooking $booking)
    {
        $guide = $this->resolveGuide(Auth::user());
        $this->authorizeOwnership($booking, $guide);

        $validated = $request->validate([
            'duration_hours' => ['nullable', 'numeric', 'min:0', 'max:48'],
        ]);

        if ($booking->status !== 'approved') {
            throw ValidationException::withMessages([
                'booking' => 'Only approved bookings can be marked completed.',
            ]);
        }

        $booking->update([
            'status' => 'completed',
            'duration_hours' => $validated['duration_hours'] ?? $booking->duration_hours,
        ]);
        AuditLogger::log('booking.completed', "Completed booking #{$booking->id}", $booking->user, $booking, [
            'duration_hours' => $booking->duration_hours,
        ]);

        return response()->json(['success' => true, 'status' => $booking->status]);
    }

    public function updateAvailability(Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:available,on-hike,unavailable,off-duty'],
        ]);

        $guide = $this->resolveGuide(Auth::user());
        $guide->update(['status' => $validated['status']]);
        AuditLogger::log('guide.availability', "Set availability to {$guide->status}", Auth::user(), $guide);

        return response()->json([
            'success' => true,
            'status' => $guide->status,
            'status_label' => $guide->status_label,
        ]);
    }

    /**
     * Tour guides can only update their own bio.
     * All other guide details (name, phone, specialty, mountain, experience, etc.)
     * are admin-managed.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'bio' => ['nullable', 'string', 'max:5000'],
        ]);

        $user = Auth::user();
        $guide = $this->resolveGuide($user);

        $bio = ($validated['bio'] ?? '') === '' ? null : $validated['bio'];

        $user->bio = $bio;
        $user->save();

        $guide->bio = $bio;
        $guide->save();

        AuditLogger::log('guide.bio_updated', 'Updated guide bio', $user, $guide);

        return response()->json([
            'success' => true,
            'full_name' => $guide->full_name,
        ]);
    }

    public function updateProfilePicture(Request $request, ProfilePictureDatabaseWriter $writer)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:10240', 'mimes:jpeg,png,gif,webp'],
        ]);

        $user = Auth::user();
        $guide = $this->resolveGuide($user);

        try {
            $writer->storeFromUploadedFile($user, $request->file('profile_picture'));
            $guide->profile_picture_path = null;
            $guide->save();
        } catch (\Throwable $e) {
            Log::error('Tour guide profile picture upload failed', [
                'user_id' => $user->id,
                'guide_id' => $guide->id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Could not save your photo.',
            ], 500);
        }

        try {
            AuditLogger::log('guide.photo_updated', 'Updated guide profile photo', $user, $guide);
        } catch (\Throwable $e) {
            Log::warning('guide.photo_updated audit log failed after successful upload', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }

        $user->refresh();

        return response()->json([
            'success' => true,
            'url' => $user->profile_picture_url,
        ]);
    }

    public function sosAlerts()
    {
        $guide = $this->resolveGuide(Auth::user());

        $alerts = SosAlert::query()
            ->where('tour_guide_id', $guide->id)
            ->with([
                'user:id,first_name,last_name,email,phone,profile_picture_path',
                'user.profilePicture:user_id,mime',
                'mountain:id,name,location',
            ])
            ->whereIn('status', [SosAlert::STATUS_OPEN, SosAlert::STATUS_ACKNOWLEDGED])
            ->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json(['success' => true, 'alerts' => $alerts]);
    }

    public function acknowledgeSosAlert(SosAlert $alert)
    {
        $guide = $this->resolveGuide(Auth::user());
        if ($alert->tour_guide_id !== $guide->id) {
            abort(403);
        }

        if ($alert->status === SosAlert::STATUS_OPEN) {
            $alert->forceFill([
                'status' => SosAlert::STATUS_ACKNOWLEDGED,
                'acknowledged_by' => Auth::id(),
                'acknowledged_at' => now(),
            ])->save();

            AuditLogger::log('guide.sos_acknowledged', "Acknowledged SOS alert #{$alert->id}", $alert->user_id, $alert, [
                'guide_id' => $guide->id,
            ]);
        }

        return back()->with('guide_status', 'SOS alert acknowledged.');
    }

    private function resolveGuide($user): TourGuide
    {
        $guide = TourGuide::query()->where('user_id', $user->id)->first();
        if (! $guide) {
            $guide = TourGuide::create([
                'user_id' => $user->id,
                'slug' => 'guide-'.$user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'specialty' => 'Trail Guide',
                'phone' => (string) ($user->phone ?? ''),
                'email' => $user->email,
                'experience_years' => 1,
                'status' => 'available',
                'avatar_gradient' => 'linear-gradient(135deg,#065f46,#10b981)',
                'sort_order' => 99,
            ]);
        }

        return $guide;
    }

    private function authorizeOwnership(HikeBooking $booking, TourGuide $guide): void
    {
        if ($booking->tour_guide_id !== $guide->id) {
            abort(403);
        }
    }
}
