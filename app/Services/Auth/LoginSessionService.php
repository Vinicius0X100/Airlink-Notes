<?php

namespace App\Services\Auth;

use App\Models\AirlinkNotes\LoginSession;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class LoginSessionService
{
    public const REMEMBER_COOKIE = 'airlink_notes_remember';

    public const UID_COOKIE = 'airlink_notes_uid';

    public function issueForUser(Usuario $usuario, Request $request): array
    {
        $plain = Str::random(64);
        $hash = hash('sha256', $plain);
        $expiresAt = now()->addDays(90);

        $onboardingCompleted = LoginSession::query()
            ->where('user_id', $usuario->id)
            ->where('onboarding_completed', true)
            ->exists();

        LoginSession::query()->create([
            'user_id' => $usuario->id,
            'token_hash' => $hash,
            'expires_at' => $expiresAt,
            'last_used_at' => now(),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'onboarding_completed' => $onboardingCompleted,
        ]);

        return [
            'remember_cookie' => Cookie::make(
                name: self::REMEMBER_COOKIE,
                value: $plain,
                minutes: 60 * 24 * 90,
                path: '/',
                domain: null,
                secure: $request->isSecure(),
                httpOnly: true,
                raw: false,
                sameSite: 'lax'
            ),
            'uid_cookie' => Cookie::make(
                name: self::UID_COOKIE,
                value: (string) $usuario->id,
                minutes: 60 * 24 * 90,
                path: '/',
                domain: null,
                secure: $request->isSecure(),
                httpOnly: false,
                raw: false,
                sameSite: 'lax'
            ),
        ];
    }

    public function restore(Request $request): ?Usuario
    {
        $plain = (string) $request->cookie(self::REMEMBER_COOKIE, '');

        if ($plain === '') {
            return null;
        }

        $hash = hash('sha256', $plain);

        $session = LoginSession::query()
            ->where('token_hash', $hash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $session) {
            return null;
        }

        $usuario = Usuario::query()->whereKey((int) $session->user_id)->first();

        if (! $usuario || (int) $usuario->status !== 1) {
            $session->update(['revoked_at' => now()]);

            return null;
        }

        $session->update([
            'last_used_at' => now(),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
        ]);

        return $usuario;
    }

    public function forgetCookies(Request $request): array
    {
        return [
            Cookie::forget(self::REMEMBER_COOKIE),
            Cookie::forget(self::UID_COOKIE),
        ];
    }

    public function revokeFromRequest(Request $request): void
    {
        $plain = (string) $request->cookie(self::REMEMBER_COOKIE, '');

        if ($plain === '') {
            return;
        }

        LoginSession::query()
            ->where('token_hash', hash('sha256', $plain))
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }

    public function markOnboardingCompleted(int $userId): void
    {
        LoginSession::query()
            ->where('user_id', $userId)
            ->update(['onboarding_completed' => true]);
    }
}
