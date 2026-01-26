<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'skill_level',
        'placement_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledModules()
    {
        return $this->belongsToMany(Module::class, 'enrollments')->withPivot('enrolled_at')->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function hasCompletedPlacement(): bool
    {
        return !is_null($this->skill_level);
    }

    public function isEnrolledIn(Module $module): bool
    {
        return $this->enrollments()->where('module_id', $module->id)->exists();
    }

    public function getSkillLevelLabel(): string
    {
        return match($this->skill_level) {
            'pemula' => 'Pemula (Beginner)',
            'menengah' => 'Menengah (Intermediate)',
            'mahir' => 'Mahir (Advanced)',
            default => 'Belum Ditentukan',
        };
    }
}
