<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExperienceFeedback extends Model
{
    public const SCORE_BAD = 'bad';
    public const SCORE_OKAY = 'okay';
    public const SCORE_GREAT = 'great';

    protected $table = 'user_experience_feedback';

    protected $fillable = [
        'user_id',
        'score',
        'dont_show_again',
        'context',
    ];

    protected $casts = [
        'dont_show_again' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
