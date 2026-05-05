<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'calories',
        'protein',
        'carbs',
        'fats',
        'eaten_at'
    ];
}
