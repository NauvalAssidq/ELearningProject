<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soal';

    protected $fillable = [
        'question_text',
        'difficulty',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the options for this question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(BankSoalOption::class);
    }

    /**
     * Get the creator of this question.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active questions only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for filtering by difficulty.
     */
    public function scopeDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Get random questions for placement quiz.
     */
    public static function getRandomForPlacement(int $count = 10)
    {
        return static::active()
            ->with('options')
            ->inRandomOrder()
            ->take($count)
            ->get();
    }
}
