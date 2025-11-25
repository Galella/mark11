<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'number',
        'owner',
        'model',
        'year',
        'status',
        'terminal_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'terminal_id' => 'integer',
    ];

    // Relationships
    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }
}