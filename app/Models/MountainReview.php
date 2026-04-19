<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MountainReview extends Model
{
    protected $fillable = [
        'user_id', 'reviewer_name', 'rating', 'body', 'mountain_id', 'hike_booking_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    public function hikeBooking(): BelongsTo
    {
        return $this->belongsTo(HikeBooking::class);
    }
}
