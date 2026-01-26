<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Assuming User model is in App\Models
use App\Models\Quiz; // Assuming Quiz model is in App\Models

class QuizAttempt extends Model
{
    protected $fillable = ['user_id', 'quiz_id', 'score', 'passed', 'started_at', 'completed_at', 'violation_count', 'metadata'];

    protected $casts = [
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
