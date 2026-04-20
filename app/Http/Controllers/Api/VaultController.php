<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vault\VaultPinRequest;
use App\Http\Requests\Vault\VaultSetPinRequest;
use App\Models\Note;
use App\Services\Auth\AuthService;
use App\Services\Content\HtmlSanitizerService;
use App\Services\Notes\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VaultController extends Controller
{
    public function status(): JsonResponse
    {
        $userId = (int) request()->user()->id;

        $hasPin = DB::connection('airlink')
            ->table('airlink_notes_vaults')
            ->where('user_id', $userId)
            ->exists();

        return response()->json(['has_pin' => $hasPin]);
    }

    public function setPin(VaultSetPinRequest $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $exists = DB::connection('airlink')
            ->table('airlink_notes_vaults')
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'PIN já configurado.'], 409);
        }

        $pin = (string) $request->validated('pin');

        DB::connection('airlink')->table('airlink_notes_vaults')->insert([
            'user_id' => $userId,
            'pin_hash' => Hash::make($pin),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true], 201);
    }

    public function changePin(Request $request, AuthService $authService): JsonResponse
    {
        $user = $request->user();
        $userId = (int) $user->id;

        $data = $request->validate([
            'password' => ['required', 'string'],
            'pin' => ['required', 'regex:/^\d{6}$/', 'confirmed'],
        ]);

        $vault = DB::connection('airlink')
            ->table('airlink_notes_vaults')
            ->where('user_id', $userId)
            ->first();

        if (! $vault) {
            return response()->json(['message' => 'PIN não configurado.'], 409);
        }

        if (! $authService->verifyUserPassword($user, (string) $data['password'])) {
            return response()->json(['message' => 'Senha inválida.'], 422);
        }

        DB::connection('airlink')
            ->table('airlink_notes_vaults')
            ->where('user_id', $userId)
            ->update([
                'pin_hash' => Hash::make((string) $data['pin']),
                'updated_at' => now(),
            ]);

        return response()->json(['ok' => true]);
    }

    public function listHidden(VaultPinRequest $request): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $pin = (string) $request->validated('pin');

        $vault = DB::connection('airlink')
            ->table('airlink_notes_vaults')
            ->where('user_id', $userId)
            ->first();

        if (! $vault) {
            return response()->json(['message' => 'PIN não configurado.'], 409);
        }

        if (! Hash::check($pin, (string) $vault->pin_hash)) {
            return response()->json(['message' => 'PIN inválido.'], 422);
        }

        $notes = DB::connection('airlink')
            ->table('airlink_notes_hidden_notes')
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->limit(200)
            ->get();

        return response()->json(['data' => $notes]);
    }

    public function hideNote(
        VaultPinRequest $request,
        Note $note,
        HtmlSanitizerService $htmlSanitizer,
        NoteService $noteService
    ): JsonResponse {
        $userId = (int) $request->user()->id;

        if ((int) $note->user_id !== $userId) {
            abort(404);
        }

        $pin = (string) $request->validated('pin');

        $vault = DB::connection('airlink')
            ->table('airlink_notes_vaults')
            ->where('user_id', $userId)
            ->first();

        if (! $vault) {
            return response()->json(['message' => 'PIN não configurado.'], 409);
        }

        if (! Hash::check($pin, (string) $vault->pin_hash)) {
            return response()->json(['message' => 'PIN inválido.'], 422);
        }

        $content = $htmlSanitizer->sanitize((string) ($note->content ?? ''));
        $title = $note->title ? trim((string) $note->title) : '';
        if ($title === '') {
            $title = $htmlSanitizer->extractTitle($content);
        }

        DB::connection('airlink')->transaction(function () use ($userId, $note, $title, $content, $noteService) {
            DB::connection('airlink')->table('airlink_notes_hidden_notes')->insert([
                'user_id' => $userId,
                'original_note_id' => $note->id,
                'title' => $title !== '' ? $title : null,
                'content' => $content !== '' ? $content : null,
                'is_pinned' => (bool) $note->is_pinned,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $noteService->deleteForUser($userId, $note);
        });

        return response()->json(['ok' => true]);
    }
}
