<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingItem extends Model
{
    protected $fillable = ['slug', 'category', 'label', 'sort_order'];
}
