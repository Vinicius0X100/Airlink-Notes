<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\RecentlyDeletedNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecentlyDeletedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $items = RecentlyDeletedNote::query()
            ->where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->orderByDesc('deleted_at')
            ->limit(200)
            ->get();

        return response()->json(['data' => $items]);
    }

    public function restore(Request $request, RecentlyDeletedNote $item): JsonResponse
    {
        $userId = (int) $request->user()->id;
        if ((int) $item->user_id !== $userId) {
            abort(404);
        }

        if ($item->expires_at && $item->expires_at->isPast()) {
            return response()->json(['message' => 'Item expirado.'], 409);
        }

        $maxSort = (int) (Note::query()->forUser($userId)->max('sort_order') ?? 0);
        $note = Note::query()->create([
            'user_id' => $userId,
            'folder_id' => $item->folder_id,
            'tag_id' => $item->tag_id,
            'sort_order' => $maxSort + 1,
            'title' => $item->title,
            'content' => $item->content ?? '',
            'is_pinned' => (bool) $item->is_pinned,
            'is_archived' => (bool) $item->is_archived,
            'version' => 1,
        ]);

        $item->delete();

        return response()->json(['note' => $note]);
    }

    public function destroy(Request $request, RecentlyDeletedNote $item): JsonResponse
    {
        $userId = (int) $request->user()->id;
        if ((int) $item->user_id !== $userId) {
            abort(404);
        }

        $item->delete();

        return response()->json(['ok' => true]);
    }
}
