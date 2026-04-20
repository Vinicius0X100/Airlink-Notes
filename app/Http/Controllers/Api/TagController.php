<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $tags = NoteTag::query()
            ->where('user_id', $userId)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $tags]);
    }

    public function store(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'color' => ['required', 'string', 'max:16', 'regex:/^#([0-9a-fA-F]{6})$/'],
        ]);

        $count = NoteTag::query()->where('user_id', $userId)->count();
        if ($count >= 50) {
            return response()->json(['message' => 'Limite de tags atingido.'], 409);
        }

        $tag = NoteTag::query()->create([
            'user_id' => $userId,
            'name' => trim((string) $data['name']),
            'color' => strtoupper((string) $data['color']),
        ]);

        return response()->json($tag, 201);
    }

    public function update(Request $request, NoteTag $tag): JsonResponse
    {
        $userId = (int) $request->user()->id;
        if ((int) $tag->user_id !== $userId) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:40'],
            'color' => ['sometimes', 'string', 'max:16', 'regex:/^#([0-9a-fA-F]{6})$/'],
        ]);

        $tag->fill([
            'name' => array_key_exists('name', $data) ? trim((string) $data['name']) : $tag->name,
            'color' => array_key_exists('color', $data) ? strtoupper((string) $data['color']) : $tag->color,
        ]);
        $tag->save();

        return response()->json($tag->fresh());
    }

    public function destroy(Request $request, NoteTag $tag): JsonResponse
    {
        $userId = (int) $request->user()->id;
        if ((int) $tag->user_id !== $userId) {
            abort(404);
        }

        Note::query()
            ->where('user_id', $userId)
            ->where('tag_id', $tag->id)
            ->update(['tag_id' => null]);

        $tag->delete();

        return response()->json(['ok' => true]);
    }
}
