<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RailTransaction extends Model
{
    use HasFactory;

    protected $table = 'rail_transactions';

    protected $fillable = [
        'terminal_id',
        'container_id',
        'rail_schedule_id',
        'wagon_id',
        'transaction_type',
        'operation_type',
        'wagon_position',
        'is_handover',
        'handover_terminal_id',
        'status',
        'transaction_time',
        'created_by',
        'remarks',
    ];

    protected $casts = [
        'is_handover' => 'boolean',
        'transaction_time' => 'datetime',
    ];

    // Relationships
    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function railSchedule(): BelongsTo
    {
        return $this->belongsTo(RailSchedule::class);
    }

    public function wagon(): BelongsTo
    {
        return $this->belongsTo(Wagon::class);
    }

    public function handoverTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'handover_terminal_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}