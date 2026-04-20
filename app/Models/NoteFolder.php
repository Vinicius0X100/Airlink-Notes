<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteFolder extends Model
{
    use SoftDeletes;

    protected $connection = 'airlink';

    protected $table = 'note_folders';

    protected $fillable = [
        'user_id',
        'name',
        'color',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class, 'folder_id');
    }
}
