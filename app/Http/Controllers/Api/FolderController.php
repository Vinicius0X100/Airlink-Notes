<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Folders\FolderStoreRequest;
use App\Http\Requests\Folders\FolderUpdateRequest;
use App\Models\NoteFolder;
use App\Services\Folders\FolderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FolderService $folderService): JsonResponse
    {
        $userId = (int) request()->user()->id;
        $folders = $folderService->listForUser($userId);

        return response()->json($folders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FolderStoreRequest $request, FolderService $folderService): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $folder = $folderService->createForUser($userId, $request->validated());

        return response()->json($folder, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FolderUpdateRequest $request, NoteFolder $folder, FolderService $folderService): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $folder = $folderService->renameForUser($userId, $folder, $request->validated());

        return response()->json($folder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NoteFolder $folder, FolderService $folderService): JsonResponse
    {
        $userId = (int) request()->user()->id;
        $folderService->deleteForUser($userId, $folder);

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request, FolderService $folderService): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $data = $request->validate([
            'ordered_ids' => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'min:1'],
        ]);

        $folderService->reorderForUser($userId, (array) $data['ordered_ids']);

        return response()->json(['ok' => true]);
    }
}
