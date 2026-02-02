<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['user_id', 'title', 'slug', 'description', 'image', 'skill_level', 'project_instruction', 'project_attachment'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function projectSubmissions()
    {
        return $this->hasMany(ProjectSubmission::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'enrollments')->withPivot('enrolled_at')->withTimestamps();
    }

    public function getSkillLevelBadgeColor(): string
    {
        return match($this->skill_level) {
            'pemula' => 'green',
            'menengah' => 'yellow',
            'mahir' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get skill level as integer for comparison.
     */
    public function getSkillLevelInt(): int
    {
        return match($this->skill_level) {
            'pemula' => 1,
            'menengah' => 2,
            'mahir' => 3,
            default => 0,
        };
    }

    /**
     * Scope to filter modules accessible by a user.
     * Users can see modules at or below their level.
     */
    public function scopeForUserLevel($query, User $user)
    {
        $levelMap = ['pemula' => 1, 'menengah' => 2, 'mahir' => 3];
        $userLevel = $levelMap[$user->skill_level] ?? 0;
        
        return $query->whereRaw("
            CASE skill_level 
                WHEN 'pemula' THEN 1 
                WHEN 'menengah' THEN 2 
                WHEN 'mahir' THEN 3 
                ELSE 0 
            END <= ?
        ", [$userLevel]);
    }

    public function isCompletedBy(User $user): bool
    {
        // 1. Check if all lessons are completed
        if (!$this->hasCompletedAllLessons($user)) {
            return false;
        }

        // 2. Check if final project is submitted and graded (grade is not null)
        // We assume every module requires a project for certification as per requirements
        $hasGradedProject = $this->projectSubmissions()
            ->where('user_id', $user->id)
            ->whereNotNull('grade')
            ->exists();
            
        return $hasGradedProject;
    }

    public function hasCompletedAllLessons(User $user): bool
    {
        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) return true;

        $completedLessons = $this->lessons()
            ->whereHas('progress', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('is_completed', true);
            })
            ->count();
            
        return $completedLessons >= $totalLessons;
    }

    public function hasPassedAllQuizzes(User $user): bool
    {
        // Get all quizzes in this module via lessons
        $quizzes = \App\Models\Quiz::whereHas('lesson', function($q) {
            $q->where('module_id', $this->id);
        })->get();

        if ($quizzes->isEmpty()) {
            return true; // No quizzes to pass
        }

        // Check if user has passed each quiz
        foreach ($quizzes as $quiz) {
            $hasPassed = $quiz->attempts()
                ->where('user_id', $user->id)
                ->where('passed', true)
                ->exists();
            
            if (!$hasPassed) {
                return false;
            }
        }

        return true;
    }
}
