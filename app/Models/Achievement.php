<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'badge_icon', 'rule_type', 'rule_json', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rule_json' => 'array',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'achievement_user')
            ->withPivot('claimed_at')
            ->withTimestamps();
    }
}
