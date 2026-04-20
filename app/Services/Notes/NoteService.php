<?php

namespace App\Services\Notes;

use App\Models\Note;
use App\Services\Content\HtmlSanitizerService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NoteService
{
    public function __construct(private readonly HtmlSanitizerService $htmlSanitizer) {}

    public function listForUser(int $userId, int $perPage = 50, ?int $folderId = null): LengthAwarePaginator
    {
        $query = Note::query()
            ->forUser($userId)
            ->orderByDesc('is_pinned')
            ->orderBy('sort_order')
            ->orderByDesc('updated_at');

        if ($folderId !== null) {
            $query->where('folder_id', $folderId);
        }

        return $query->paginate($perPage);
    }

    public function createForUser(int $userId, array $data): Note
    {
        $content = $this->htmlSanitizer->sanitize((string) $data['content']);
        $title = isset($data['title']) ? trim((string) $data['title']) : '';

        if ($title === '') {
            $title = $this->htmlSanitizer->extractTitle($content);
        }

        $minSort = (int) (Note::query()->forUser($userId)->min('sort_order') ?? 0);
        $sortOrder = $minSort - 1;

        return Note::query()->create([
            'user_id' => $userId,
            'folder_id' => $data['folder_id'] ?? null,
            'tag_id' => $data['tag_id'] ?? null,
            'sort_order' => $sortOrder,
            'title' => $title !== '' ? $title : null,
            'content' => $content,
            'is_pinned' => (bool) ($data['is_pinned'] ?? false),
            'is_archived' => (bool) ($data['is_archived'] ?? false),
            'version' => 1,
        ]);
    }

    public function updateForUser(int $userId, Note $note, array $data): Note
    {
        if ($note->user_id !== $userId) {
            abort(404);
        }

        $content = $note->content;
        if (array_key_exists('content', $data)) {
            $content = $this->htmlSanitizer->sanitize((string) $data['content']);
        }

        $title = $note->title;
        if (array_key_exists('title', $data)) {
            $incoming = trim((string) $data['title']);
            $title = $incoming !== '' ? $incoming : null;
        } elseif ($title === null || $title === '') {
            $derived = $this->htmlSanitizer->extractTitle($content);
            $title = $derived !== '' ? $derived : null;
        }

        $note->fill([
            'folder_id' => array_key_exists('folder_id', $data) ? $data['folder_id'] : $note->folder_id,
            'tag_id' => array_key_exists('tag_id', $data) ? $data['tag_id'] : $note->tag_id,
            'sort_order' => array_key_exists('sort_order', $data) ? (int) $data['sort_order'] : $note->sort_order,
            'title' => $title,
            'content' => $content,
            'is_pinned' => array_key_exists('is_pinned', $data) ? (bool) $data['is_pinned'] : $note->is_pinned,
            'is_archived' => array_key_exists('is_archived', $data) ? (bool) $data['is_archived'] : $note->is_archived,
        ]);

        $note->save();

        return $note->fresh();
    }

    public function autosaveForUser(int $userId, Note $note, string $content): Note
    {
        if ($note->user_id !== $userId) {
            abort(404);
        }

        $content = $this->htmlSanitizer->sanitize($content);
        $title = $note->title;

        if ($title === null || $title === '') {
            $derived = $this->htmlSanitizer->extractTitle($content);
            $title = $derived !== '' ? $derived : null;
        }

        Note::query()
            ->whereKey($note->id)
            ->where('user_id', $userId)
            ->update([
                'content' => $content,
                'title' => $title,
                'version' => DB::raw('version + 1'),
                'updated_at' => now(),
            ]);

        return $note->fresh();
    }

    public function deleteForUser(int $userId, Note $note): void
    {
        if ($note->user_id !== $userId) {
            abort(404);
        }

        $note->delete();
    }

    public function reorderForUser(int $userId, array $orderedIds): void
    {
        $orderedIds = array_values(array_unique(array_map('intval', $orderedIds)));
        if ($orderedIds === []) {
            return;
        }

        $count = Note::query()
            ->forUser($userId)
            ->whereIn('id', $orderedIds)
            ->count();

        if ($count !== count($orderedIds)) {
            abort(422, 'Notas inválidas.');
        }

        DB::connection('airlink')->transaction(function () use ($userId, $orderedIds) {
            foreach ($orderedIds as $i => $id) {
                Note::query()
                    ->where('user_id', $userId)
                    ->whereKey($id)
                    ->update(['sort_order' => $i]);
            }
        });
    }
}
