<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappNumber extends Model
{
    protected $fillable = [
        'name', 'phone_number', 'phone_number_id', 'waba_id', 'access_token',
        'purpose', 'is_active', 'coexistence_enabled', 'connection_status',
        'connected_at', 'last_error',
    ];

    protected $hidden = ['access_token'];

    protected $casts = [
        'is_active'           => 'boolean',
        'coexistence_enabled' => 'boolean',
        'connected_at'        => 'datetime',
        'access_token'        => 'encrypted',
    ];

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
