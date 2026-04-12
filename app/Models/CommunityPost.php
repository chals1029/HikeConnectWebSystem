<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunityPost extends Model
{
    protected $fillable = [
        'user_id', 'author_name', 'author_initials', 'body', 'mountain_id', 'avatar_gradient',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mountain(): BelongsTo
    {
        return $this->belongsTo(Mountain::class);
    }
}
