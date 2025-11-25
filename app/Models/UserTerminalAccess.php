<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTerminalAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'terminal_id',
        'role_id',
    ];

    protected $table = 'user_terminal_accesses';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}