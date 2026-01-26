<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementQuiz extends Model
{
    protected $fillable = ['title', 'description', 'time_limit', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'time_limit' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(PlacementQuestion::class)->orderBy('order');
    }

    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }
}
