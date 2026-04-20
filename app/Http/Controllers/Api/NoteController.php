<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\NoteAutosaveRequest;
use App\Http\Requests\Notes\NoteStoreRequest;
use App\Http\Requests\Notes\NoteUpdateRequest;
use App\Models\Note;
use App\Models\NoteTag;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(NoteService $noteService): JsonResponse
    {
        $userId = (int) request()->user()->id;

        $perPage = request()->query('per_page');
        $perPage = is_numeric($perPage) ? (int) $perPage : 50;
        $perPage = max(1, min($perPage, 200));

        $folderId = request()->query('folder_id');
        $folderId = is_numeric($folderId) ? (int) $folderId : null;

        $notes = $noteService->listForUser($userId, $perPage, $folderId);

        return response()->json($notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteStoreRequest $request, NoteService $noteService): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $data = $request->validated();
        if (array_key_exists('tag_id', $data) && $data['tag_id'] !== null) {
            $exists = NoteTag::query()
                ->where('user_id', $userId)
                ->whereKey((int) $data['tag_id'])
                ->exists();
            if (! $exists) {
                abort(422, 'Tag inválida.');
            }
        }

        $note = $noteService->createForUser($userId, $data);

        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note): JsonResponse
    {
        $userId = (int) request()->user()->id;

        if ($note->user_id !== $userId) {
            abort(404);
        }

        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteUpdateRequest $request, Note $note, NoteService $noteService): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $data = $request->validated();
        if (array_key_exists('tag_id', $data) && $data['tag_id'] !== null) {
            $exists = NoteTag::query()
                ->where('user_id', $userId)
                ->whereKey((int) $data['tag_id'])
                ->exists();
            if (! $exists) {
                abort(422, 'Tag inválida.');
            }
        }

        $note = $noteService->updateForUser($userId, $note, $data);

        return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note, NoteService $noteService): JsonResponse
    {
        $userId = (int) request()->user()->id;

        $noteService->deleteForUser($userId, $note);

        return response()->json(['ok' => true]);
    }

    public function autosave(NoteAutosaveRequest $request, Note $note, NoteService $noteService): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $content = $request->validated('content');
        $title = $request->validated('title');
        $note = $noteService->autosaveForUser($userId, $note, (string) ($content ?? ''), $title);

        return response()->json([
            'id' => $note->id,
            'version' => $note->version,
            'updated_at' => $note->updated_at,
            'title' => $note->title,
        ]);
    }

    public function reorder(Request $request, NoteService $noteService): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $data = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'min:1'],
        ]);

        $noteService->reorderForUser($userId, (array) $data['ordered_ids']);

        return response()->json(['ok' => true]);
    }
}
