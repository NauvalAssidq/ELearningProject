<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementQuestion extends Model
{
    protected $fillable = ['placement_quiz_id', 'question_text', 'difficulty', 'order'];

    protected $casts = [
        'order' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(PlacementQuiz::class, 'placement_quiz_id');
    }

    public function options()
    {
        return $this->hasMany(PlacementOption::class);
    }

    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
