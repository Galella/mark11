<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RailSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_id',
        'schedule_code',
        'origin_terminal_id',
        'destination_terminal_id',
        'departure_time',
        'arrival_time',
        'status',
        'expected_teus',
        'loaded_teus',
        'special_instructions',
        'is_active',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'is_active' => 'boolean',
        'expected_teus' => 'integer',
        'loaded_teus' => 'integer',
        'special_instructions' => 'array',
    ];

    // Relationships
    public function train(): BelongsTo
    {
        return $this->belongsTo(Train::class);
    }

    public function originTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'origin_terminal_id');
    }

    public function destinationTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'destination_terminal_id');
    }

    public function railTransactions(): HasMany
    {
        return $this->hasMany(RailTransaction::class);
    }
}