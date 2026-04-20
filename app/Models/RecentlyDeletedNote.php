<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentlyDeletedNote extends Model
{
    protected $connection = 'airlink';

    protected $table = 'airlink_notes_recently_deleted_notes';

    protected $fillable = [
        'user_id',
        'original_note_id',
        'folder_id',
        'tag_id',
        'title',
        'content',
        'is_pinned',
        'is_archived',
        'deleted_at',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_archived' => 'boolean',
        'deleted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
