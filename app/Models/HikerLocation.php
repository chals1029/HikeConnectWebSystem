<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HikerLocation extends Model
{
    protected $fillable = [
        'user_id', 'hike_booking_id', 'mountain_id',
        'lat', 'lng', 'accuracy_m', 'altitude_m', 'speed_mps', 'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
            'accuracy_m' => 'float',
            'altitude_m' => 'float',
            'speed_mps' => 'float',
            'recorded_at' => 'datetime',
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

    public function hikeBooking(): BelongsTo
    {
        return $this->belongsTo(HikeBooking::class);
    }
}
