<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourGuide extends Model
{
    protected $fillable = [
        'user_id', 'slug', 'first_name', 'last_name', 'specialty', 'phone', 'email',
        'bio', 'experience_years', 'status', 'mountain_id', 'avatar_gradient',
        'profile_picture_path', 'sort_order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    public function getProfilePictureUrlAttribute(): ?string
    {
        $path = $this->profile_picture_path ?: $this->user?->profile_picture_path;
        if (! $path) {
            return null;
        }

        $relative = 'storage/'.ltrim(str_replace('\\', '/', $path), '/');

        if (! app()->runningInConsole()) {
            $request = request();
            if ($request && $request->getHttpHost()) {
                $base = rtrim($request->getSchemeAndHttpHost().$request->getBasePath(), '/');

                return $base.'/'.$relative;
            }
        }

        return asset($relative);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Available',
            'on-hike' => 'On a hike',
            'unavailable' => 'Unavailable',
            'off-duty' => 'Off duty',
            default => ucfirst((string) $this->status),
        };
    }
}
