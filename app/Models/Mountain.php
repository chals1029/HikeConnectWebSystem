<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mountain extends Model
{
    public const SAFETY_OPEN = 'open';
    public const SAFETY_CAUTION = 'caution';
    public const SAFETY_CLOSED = 'closed';
    public const SAFETY_BAD_WEATHER = 'bad_weather';
    public const SAFETY_HIGH_RISK = 'high_risk';

    public const SAFETY_STATUSES = [
        self::SAFETY_OPEN => 'Open',
        self::SAFETY_CAUTION => 'Caution',
        self::SAFETY_CLOSED => 'Closed',
        self::SAFETY_BAD_WEATHER => 'Bad Weather',
        self::SAFETY_HIGH_RISK => 'High Risk',
    ];

    protected $fillable = [
        'slug', 'name', 'short_description', 'location', 'difficulty', 'rating', 'image_path',
        'status', 'safety_status', 'safety_note', 'elevation_label', 'elevation_meters', 'duration_label', 'trail_type_label',
        'best_time_label', 'full_description', 'jumpoff_name', 'jumpoff_address',
        'jumpoff_meeting_time', 'jumpoff_notes', 'jumpoff_lat', 'jumpoff_lng',
        'summit_lat', 'summit_lng', 'open_meteo_lat', 'open_meteo_lng', 'gear',
        'trail_plan', 'trail_gear_list', 'registration_fee_per_person', 'environmental_fee_per_person',
        'local_fee_per_person', 'guide_fee_per_person', 'guide_fee_per_group', 'pricing_source_note',
        'pricing_last_verified_on', 'emergency_contact', 'sort_order',
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
            'registration_fee_per_person' => 'float',
            'environmental_fee_per_person' => 'float',
            'local_fee_per_person' => 'float',
            'guide_fee_per_person' => 'float',
            'guide_fee_per_group' => 'float',
            'pricing_last_verified_on' => 'date',
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

    public function sosAlerts(): HasMany
    {
        return $this->hasMany(SosAlert::class);
    }

    public function meteoLat(): float
    {
        return (float) ($this->open_meteo_lat ?? $this->jumpoff_lat);
    }

    public function meteoLng(): float
    {
        return (float) ($this->open_meteo_lng ?? $this->jumpoff_lng);
    }

    public function getSafetyStatusLabelAttribute(): string
    {
        return self::SAFETY_STATUSES[$this->safety_status ?? self::SAFETY_OPEN] ?? ucfirst((string) $this->safety_status);
    }

    public function hasSafetyWarning(): bool
    {
        return ($this->safety_status ?? self::SAFETY_OPEN) !== self::SAFETY_OPEN;
    }
}
