<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteTag extends Model
{
    protected $connection = 'airlink';

    protected $table = 'note_tags';

    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];
}
