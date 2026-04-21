<?php

namespace App\Services\Folders;

use App\Models\Note;
use App\Models\NoteFolder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

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

    public function createForUser(int $userId, array $data): NoteFolder
    {
        $name = trim((string) $data['name']);
        $maxSort = (int) (NoteFolder::query()->where('user_id', $userId)->max('sort_order') ?? 0);

        try {
            return NoteFolder::query()->create([
                'user_id' => $userId,
                'name' => $name,
                'icon_emoji' => array_key_exists('icon_emoji', $data) ? (string) ($data['icon_emoji'] ?? '') : null,
                'color' => array_key_exists('color', $data) && $data['color'] ? strtoupper((string) $data['color']) : null,
                'sort_order' => $maxSort + 1,
            ]);
        } catch (QueryException $e) {
            if ($this->isDuplicateKey($e)) {
                abort(422, 'Já existe uma pasta com esse nome.');
            }

            throw $e;
        }
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

        if (array_key_exists('icon_emoji', $data)) {
            $update['icon_emoji'] = $data['icon_emoji'] ? trim((string) $data['icon_emoji']) : null;
        }

        if (array_key_exists('sort_order', $data)) {
            $update['sort_order'] = (int) $data['sort_order'];
        }

        if ($update !== []) {
            try {
                $folder->update($update);
            } catch (QueryException $e) {
                if ($this->isDuplicateKey($e)) {
                    abort(422, 'Já existe uma pasta com esse nome.');
                }

                throw $e;
            }
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

    public function reorderForUser(int $userId, array $orderedIds): void
    {
        $orderedIds = array_values(array_unique(array_map('intval', $orderedIds)));
        if ($orderedIds === []) {
            return;
        }

        $count = NoteFolder::query()
            ->where('user_id', $userId)
            ->whereIn('id', $orderedIds)
            ->count();

        if ($count !== count($orderedIds)) {
            abort(422, 'Pastas inválidas.');
        }

        DB::connection('airlink')->transaction(function () use ($userId, $orderedIds) {
            foreach ($orderedIds as $i => $id) {
                NoteFolder::query()
                    ->where('user_id', $userId)
                    ->whereKey($id)
                    ->update(['sort_order' => $i]);
            }
        });
    }

    private function isDuplicateKey(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;
        $driverCode = $e->errorInfo[1] ?? null;

        return $sqlState === '23000' && (int) $driverCode === 1062;
    }
}
