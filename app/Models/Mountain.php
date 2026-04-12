<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mountain extends Model
{
    protected $fillable = [
        'slug', 'name', 'short_description', 'location', 'difficulty', 'rating', 'image_path',
        'status', 'elevation_label', 'elevation_meters', 'duration_label', 'trail_type_label',
        'best_time_label', 'full_description', 'jumpoff_name', 'jumpoff_address',
        'jumpoff_meeting_time', 'jumpoff_notes', 'jumpoff_lat', 'jumpoff_lng',
        'summit_lat', 'summit_lng', 'open_meteo_lat', 'open_meteo_lng', 'gear',
        'trail_plan', 'trail_gear_list', 'emergency_contact', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'jumpoff_lat' => 'float',
            'jumpoff_lng' => 'float',
            'summit_lat' => 'float',
            'summit_lng' => 'float',
            'open_meteo_lat' => 'float',
            'open_meteo_lng' => 'float',
            'gear' => 'array',
            'trail_plan' => 'array',
            'trail_gear_list' => 'array',
        ];
    }

    public function tourGuides(): HasMany
    {
        return $this->hasMany(TourGuide::class);
    }

    public function hikeBookings(): HasMany
    {
        return $this->hasMany(HikeBooking::class);
    }

    public function meteoLat(): float
    {
        return (float) ($this->open_meteo_lat ?? $this->jumpoff_lat);
    }

    public function meteoLng(): float
    {
        return (float) ($this->open_meteo_lng ?? $this->jumpoff_lng);
    }
}
