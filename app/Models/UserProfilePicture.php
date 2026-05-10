<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfilePicture extends Model
{
    protected $table = 'user_profile_pictures';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'data',
        'mime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
