<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourGuide extends Model
{
    protected $fillable = [
        'slug', 'first_name', 'last_name', 'specialty', 'phone', 'experience_years',
        'status', 'mountain_id', 'avatar_gradient', 'sort_order',
    ];

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }

    public function hikeBookings(): HasMany
    {
        return $this->hasMany(HikeBooking::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1).substr($this->last_name, 0, 1));
    }
}
