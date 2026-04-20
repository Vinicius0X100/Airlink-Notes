<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $connection = 'airlink';

    protected $fillable = [
        'user_id',
        'folder_id',
        'sort_order',
        'tag_id',
        'title',
        'content',
        'is_pinned',
        'is_archived',
        'version',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_archived' => 'boolean',
        'version' => 'integer',
        'sort_order' => 'integer',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function folder()
    {
        return $this->belongsTo(NoteFolder::class, 'folder_id');
    }

    public function tag()
    {
        return $this->belongsTo(NoteTag::class, 'tag_id');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
