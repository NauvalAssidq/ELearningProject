<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'description',
        'media_url',
        'duration',
        'content',
        'order',
        'is_preview',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'duration' => 'integer',
        'order' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->progress()->where('user_id', $user->id)->where('is_completed', true)->exists();
    }

    public function getProgressFor(User $user): ?LessonProgress
    {
        return $this->progress()->where('user_id', $user->id)->first();
    }
}
