<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $table = 'usuarios';

    protected $connection = 'sacratech_contas';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'password_hash',
        'status',
        'dois_fatores_ativo',
        'segredo_dois_fatores',
    ];

    protected $hidden = [
        'password_hash',
        'segredo_dois_fatores',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'integer',
        'dois_fatores_ativo' => 'integer',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'user_id');
    }
}
