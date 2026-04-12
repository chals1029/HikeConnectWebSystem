<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HikeBooking extends Model
{
    protected $fillable = [
        'user_id', 'mountain_id', 'tour_guide_id', 'hike_on', 'hikers_count',
        'notes', 'status', 'rating', 'review_text', 'duration_hours',
    ];

    protected function casts(): array
    {
        return [
            'hike_on' => 'date',
            'duration_hours' => 'float',
        ];
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
