<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GateTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'terminal_id',
        'container_id',
        'transaction_type',
        'truck_number',
        'driver_name',
        'driver_license',
        'seal_number',
        'status',
        'transaction_time',
        'created_by',
        'remarks',
    ];

    protected $casts = [
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}