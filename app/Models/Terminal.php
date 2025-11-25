<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'address',
        'contact_person',
        'contact_phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function containers(): HasMany
    {
        return $this->hasMany(Container::class);
    }

    public function activeInventories(): HasMany
    {
        return $this->hasMany(ActiveInventory::class);
    }

    public function gateTransactions(): HasMany
    {
        return $this->hasMany(GateTransaction::class);
    }

    public function railTransactions(): HasMany
    {
        return $this->hasMany(RailTransaction::class);
    }

    public function railSchedulesOrigin(): HasMany
    {
        return $this->hasMany(RailSchedule::class, 'origin_terminal_id');
    }

    public function railSchedulesDestination(): HasMany
    {
        return $this->hasMany(RailSchedule::class, 'destination_terminal_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserTerminalAccess::class);
    }
}