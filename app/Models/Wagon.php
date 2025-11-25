<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wagon extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_id',
        'wagon_number',
        'wagon_type',
        'teu_capacity',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'teu_capacity' => 'integer',
    ];

    // Relationships
    public function train(): BelongsTo
    {
        return $this->belongsTo(Train::class);
    }

    public function railTransactions(): HasMany
    {
        return $this->hasMany(RailTransaction::class);
    }
}