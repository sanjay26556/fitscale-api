<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStat extends Model
{
    protected $fillable = [
        'user_id',
        'protein',
        'calories',
        'steps',
        'score'
    ];
}