<?php

namespace App\Services\Folders;

use App\Models\Note;
use App\Models\NoteFolder;
use Illuminate\Database\Eloquent\Collection;

class FolderService
{
    public function listForUser(int $userId): Collection
    {
        return NoteFolder::query()
            ->where('user_id', $userId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function createForUser(int $userId, string $name): NoteFolder
    {
        $name = trim($name);

        return NoteFolder::query()->create([
            'user_id' => $userId,
            'name' => $name,
            'sort_order' => 0,
        ]);
    }

    public function renameForUser(int $userId, NoteFolder $folder, array $data): NoteFolder
    {
        if ((int) $folder->user_id !== $userId) {
            abort(404);
        }

        $update = [];

        if (array_key_exists('name', $data)) {
            $update['name'] = trim((string) $data['name']);
        }

        if (array_key_exists('color', $data)) {
            $update['color'] = $data['color'] ? strtoupper((string) $data['color']) : null;
        }

        if ($update !== []) {
            $folder->update($update);
        }

        return $folder->fresh();
    }

    public function deleteForUser(int $userId, NoteFolder $folder): void
    {
        if ((int) $folder->user_id !== $userId) {
            abort(404);
        }

        Note::query()
            ->where('user_id', $userId)
            ->where('folder_id', $folder->id)
            ->update(['folder_id' => null]);

        $folder->delete();
    }
}
