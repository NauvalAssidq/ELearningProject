<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelUpAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'from_level',
        'to_level',
        'score',
        'passed',
    ];

    protected $casts = [
        'passed' => 'boolean',
    ];

    /**
     * Get the user who made this attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
