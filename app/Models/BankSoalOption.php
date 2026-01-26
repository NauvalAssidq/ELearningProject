<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankSoalOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_soal_id',
        'option_text',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Get the question this option belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(BankSoal::class, 'bank_soal_id');
    }
}
