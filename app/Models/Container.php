<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'type',
        'size_type',
        'category',
        'status',
        'iso_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function activeInventory(): HasOne
    {
        return $this->hasOne(ActiveInventory::class);
    }

    public function gateTransactions(): HasMany
    {
        return $this->hasMany(GateTransaction::class);
    }

    public function railTransactions(): HasMany
    {
        return $this->hasMany(RailTransaction::class);
    }

    // Scope to validate ISO 6346 check digit
    public function scopeValidIsoNumber($query, $containerNumber)
    {
        // Implement ISO 6346 check digit validation
        $query->where('number', $containerNumber)
              ->whereRaw("check_iso_6346_digit(?) = RIGHT(number, 1)", [$containerNumber]);
    }
}