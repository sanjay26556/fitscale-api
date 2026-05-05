<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    protected $fillable = [
        'user_id',
        'weight',
        'recorded_at'
    ];
}
