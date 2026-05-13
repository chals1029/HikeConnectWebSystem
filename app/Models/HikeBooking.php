<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HikeBooking extends Model
{
    protected $fillable = [
        'user_id', 'mountain_id', 'tour_guide_id', 'hike_on', 'hikers_count',
        'notes', 'status', 'rating', 'review_text', 'duration_hours',
        'checked_in_at', 'checked_out_at', 'expected_price',
    ];

    protected function casts(): array
    {
        return [
            'hike_on' => 'date',
            'duration_hours' => 'float',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'expected_price' => 'float',
        ];
    }

    public function canCheckIn(): bool
    {
        return $this->status === 'approved'
            && $this->checked_in_at === null
            && $this->isHikeDay();
    }

    public function canCheckOut(): bool
    {
        return $this->status === 'in_progress' && $this->checked_in_at !== null && $this->checked_out_at === null;
    }

    /**
     * True only on the actual hike day. We compare against "today" in the
     * configured app timezone so a hiker booking in Manila isn't blocked
     * because UTC is still on the previous date.
     */
    public function isHikeDay(): bool
    {
        if (! $this->hike_on) {
            return false;
        }

        $tz = config('app.timezone', 'UTC');

        return $this->hike_on->copy()->setTimezone($tz)->isSameDay(now($tz));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    public function tourGuide(): BelongsTo
    {
        return $this->belongsTo(TourGuide::class);
    }

    public function mountainReview(): HasOne
    {
        return $this->hasOne(MountainReview::class);
    }

    public function sosAlerts(): HasMany
    {
        return $this->hasMany(SosAlert::class);
    }

    public function getUiTabAttribute(): string
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }
        if ($this->status === 'completed' || $this->hike_on->lt(today())) {
            return 'past';
        }

        return 'upcoming';
    }
}
