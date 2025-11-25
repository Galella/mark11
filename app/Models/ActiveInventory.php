<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'terminal_id',
        'current_location',
        'handling_status',
        'last_transaction_id',
        'last_transaction_type',
        'in_time',
        'out_time',
        'dwell_time_start',
    ];

    protected $casts = [
        'in_time' => 'datetime',
        'out_time' => 'datetime',
        'dwell_time_start' => 'datetime',
    ];

    // Relationships
    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    // Calculate dwell time
    public function getDwellTimeAttribute()
    {
        if ($this->out_time) {
            return $this->in_time->diffInHours($this->out_time);
        }
        
        return $this->in_time->diffInHours(now());
    }
}