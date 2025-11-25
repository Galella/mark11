<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Train extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_number',
        'name',
        'operator',
        'total_wagons',
        'max_teus_capacity',
        'route_from',
        'route_to',
        'status',
        'commissioning_date',
        'next_maintenance_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'commissioning_date' => 'date',
        'next_maintenance_date' => 'date',
    ];

    // Relationships
    public function wagons(): HasMany
    {
        return $this->hasMany(Wagon::class);
    }

    public function railSchedules(): HasMany
    {
        return $this->hasMany(RailSchedule::class);
    }

    public function railTransactions(): HasMany
    {
        return $this->hasMany(RailTransaction::class);
    }
}