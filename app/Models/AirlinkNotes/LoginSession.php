<?php

namespace App\Models\AirlinkNotes;

use Illuminate\Database\Eloquent\Model;

class LoginSession extends Model
{
    protected $connection = 'airlink';

    protected $table = 'airlink_notes_login_sessions';

    protected $fillable = [
        'user_id',
        'token_hash',
        'expires_at',
        'last_used_at',
        'revoked_at',
        'ip',
        'user_agent',
        'onboarding_completed',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
        'onboarding_completed' => 'boolean',
    ];
}
