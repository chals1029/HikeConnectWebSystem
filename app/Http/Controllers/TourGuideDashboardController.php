<?php

namespace App\Http\Controllers;

use App\Models\HikeBooking;
use App\Models\Mountain;
use App\Models\TourGuide;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            ->with(['user', 'mountain', 'mountainReview'])
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

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,gif,webp'],
        ]);

        $user = Auth::user();
        $guide = $this->resolveGuide($user);

        if ($user->profile_picture_path) {
            Storage::disk('public')->delete($user->profile_picture_path);
        }

        $path = $request->file('profile_picture')->store('profile-pictures/'.$user->id, 'public');
        $user->profile_picture_path = $path;
        $user->save();

        $guide->profile_picture_path = $path;
        $guide->save();
        AuditLogger::log('guide.photo_updated', 'Updated guide profile photo', $user, $guide);

        return response()->json([
            'success' => true,
            'url' => $user->profile_picture_url,
        ]);
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
