<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Hidden(['password', 'remember_token', 'password_change_code'])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_HIKER = 'hiker';

    public const ROLE_TOUR_GUIDE = 'tour_guide';

    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'bio',
        'role',
        'profile_picture_path',
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        if (! $this->profile_picture_path) {
            return null;
        }

        $relative = 'storage/'.ltrim(str_replace('\\', '/', $this->profile_picture_path), '/');

        if (! app()->runningInConsole()) {
            $request = request();
            if ($request && $request->getHttpHost()) {
                $base = rtrim($request->getSchemeAndHttpHost().$request->getBasePath(), '/');

                return $base.'/'.$relative;
            }
        }

        return asset($relative);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_TOUR_GUIDE => 'Tour guide',
            default => 'Hiker',
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTourGuide(): bool
    {
        return $this->role === self::ROLE_TOUR_GUIDE;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'password_change_code_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hikeBookings(): HasMany
    {
        return $this->hasMany(HikeBooking::class);
    }

    public function communityPosts(): HasMany
    {
        return $this->hasMany(CommunityPost::class);
    }

    public function mountainReviews(): HasMany
    {
        return $this->hasMany(MountainReview::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'achievement_user')
            ->withPivot('claimed_at')
            ->withTimestamps();
    }

    public function claimedAchievementsCount(): int
    {
        return $this->achievements()->wherePivotNotNull('claimed_at')->count();
    }

    public function tourGuide(): HasOne
    {
        return $this->hasOne(TourGuide::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function locationPings(): HasMany
    {
        return $this->hasMany(HikerLocation::class);
    }

    public function isHiker(): bool
    {
        return $this->role === self::ROLE_HIKER || $this->role === null;
    }
}
