<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = ['user_id', 'lesson_id', 'is_completed', 'quiz_attempt_id', 'completed_at'];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function markComplete(QuizAttempt $attempt = null)
    {
        $this->update([
            'is_completed' => true,
            'quiz_attempt_id' => $attempt?->id,
            'completed_at' => now(),
        ]);
    }
}
