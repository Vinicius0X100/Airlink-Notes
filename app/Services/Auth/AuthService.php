<?php

namespace App\Services\Auth;

use App\Models\Usuario;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class AuthService
{
    public function verifyUserPassword(Usuario $usuario, string $password): bool
    {
        $storedHash = $this->getStoredPasswordHash($usuario);

        return $this->verifyPassword($password, $storedHash);
    }

    public function checkEmail(string $email): array
    {
        $usuario = Usuario::query()->where('email', $email)->first();

        if (! $usuario) {
            return ['exists' => false];
        }

        if ((int) $usuario->status !== 1) {
            return ['exists' => true, 'inactive' => true];
        }

        return [
            'exists' => true,
            'inactive' => false,
            'two_factor_active' => (int) $usuario->dois_fatores_ativo === 1,
            'nome' => $usuario->nome,
        ];
    }

    public function attemptLogin(string $email, string $password): array
    {
        $usuario = Usuario::query()->where('email', $email)->first();

        if (! $usuario) {
            return ['ok' => false];
        }

        if ((int) $usuario->status !== 1) {
            return ['ok' => false, 'inactive' => true];
        }

        $storedHash = $this->getStoredPasswordHash($usuario);

        if (! $this->verifyPassword($password, $storedHash)) {
            return ['ok' => false];
        }

        if ((int) $usuario->dois_fatores_ativo !== 1) {
            $token = $usuario->createToken('web')->plainTextToken;

            return [
                'ok' => true,
                'requires_2fa' => false,
                'token' => $token,
                'usuario' => $usuario,
            ];
        }

        $challengeId = (string) Str::uuid();

        Cache::put($this->challengeCacheKey($challengeId), [
            'user_id' => $usuario->id,
            'email' => $usuario->email,
        ], now()->addMinutes(5));
        Cache::put($this->legacyEmailCacheKey($email), [
            'challenge_id' => $challengeId,
            'user_id' => $usuario->id,
        ], now()->addMinutes(5));

        return [
            'ok' => true,
            'requires_2fa' => true,
            'challenge_id' => $challengeId,
        ];
    }

    public function verify2fa(string $email, string $otp, ?string $challengeId = null): array
    {
        $usuario = Usuario::query()->where('email', $email)->first();

        if (! $usuario) {
            return ['ok' => false];
        }

        if ((int) $usuario->status !== 1) {
            return ['ok' => false, 'inactive' => true];
        }

        if ((int) $usuario->dois_fatores_ativo !== 1 || ! $usuario->segredo_dois_fatores) {
            return ['ok' => false];
        }

        $challengeId = $challengeId ?: $this->resolveChallengeIdByEmail($email);

        if (! $this->challengeIsValidForUser($usuario->id, $email, $challengeId)) {
            return ['ok' => false];
        }

        $otp = preg_replace('/\D+/', '', $otp) ?? $otp;
        $parsed = $this->parseTwoFactorSecret((string) $usuario->segredo_dois_fatores);

        $isValid = $this->verifyTotp(
            (string) $parsed['secret'],
            $otp,
            [
                'digits' => $parsed['digits'] ?? null,
                'period' => $parsed['period'] ?? null,
                'algorithm' => $parsed['algorithm'] ?? null,
            ]
        );

        if (! $isValid) {
            return ['ok' => false, 'invalid_otp' => true];
        }

        $token = $usuario->createToken('web')->plainTextToken;

        if ($challengeId) {
            Cache::forget($this->challengeCacheKey($challengeId));
        }
        Cache::forget($this->legacyEmailCacheKey($email));

        return [
            'ok' => true,
            'token' => $token,
            'usuario' => $usuario,
        ];
    }

    private function challengeIsValidForUser(int $userId, string $email, ?string $challengeId): bool
    {
        if (! $challengeId) {
            return false;
        }

        $payload = Cache::get($this->challengeCacheKey($challengeId));

        return is_array($payload)
            && (int) ($payload['user_id'] ?? 0) === $userId
            && strtolower((string) ($payload['email'] ?? '')) === strtolower($email);
    }

    private function challengeCacheKey(string $challengeId): string
    {
        return 'airlink_notes:login_2fa_challenge:'.$challengeId;
    }

    private function legacyEmailCacheKey(string $email): string
    {
        return 'airlink_notes:login_2fa_email:'.sha1(strtolower($email));
    }

    private function resolveChallengeIdByEmail(string $email): ?string
    {
        $payload = Cache::get($this->legacyEmailCacheKey($email));

        if (! is_array($payload)) {
            return null;
        }

        $challenge = $payload['challenge_id'] ?? null;

        return is_string($challenge) && $challenge !== '' ? $challenge : null;
    }

    private function parseTwoFactorSecret(string $raw): array
    {
        $value = trim($raw);

        $query = [];

        if (str_starts_with($value, 'otpauth://')) {
            $parts = parse_url($value);

            if (is_array($parts) && isset($parts['query'])) {
                parse_str((string) $parts['query'], $query);
            }

            if (is_array($query) && isset($query['secret'])) {
                $value = (string) $query['secret'];
            }
        }

        $secret = strtoupper(preg_replace('/\s+/', '', $value) ?? $value);

        $result = ['secret' => $secret];

        if (is_array($query) && isset($query['digits']) && is_numeric($query['digits'])) {
            $result['digits'] = (int) $query['digits'];
        }

        if (is_array($query) && isset($query['period']) && is_numeric($query['period'])) {
            $result['period'] = (int) $query['period'];
        }

        if (is_array($query) && isset($query['algorithm'])) {
            $algorithm = strtoupper((string) $query['algorithm']);
            $result['algorithm'] = match ($algorithm) {
                'SHA256' => 'sha256',
                'SHA512' => 'sha512',
                default => 'sha1',
            };
        }

        return $result;
    }

    private function verifyTotp(string $secret, string $otp, array $preferred = []): bool
    {
        $otp = preg_replace('/\D+/', '', $otp) ?? $otp;
        $digitsFromOtp = strlen($otp);

        if ($digitsFromOtp < 6 || $digitsFromOtp > 8) {
            return false;
        }

        $candidates = [];

        $digits = isset($preferred['digits']) && is_numeric($preferred['digits']) ? (int) $preferred['digits'] : null;
        $period = isset($preferred['period']) && is_numeric($preferred['period']) ? (int) $preferred['period'] : null;
        $algorithm = is_string($preferred['algorithm'] ?? null) ? (string) $preferred['algorithm'] : null;

        if ($digits) {
            $candidates[] = ['digits' => $digits, 'period' => $period ?: 30, 'algorithm' => $algorithm ?: 'sha1', 'window' => 2];
        }

        $periods = array_values(array_unique(array_filter([$period, 30, 60])));
        $algorithms = array_values(array_unique(array_filter([$algorithm, 'sha1', 'sha256', 'sha512'])));
        $windows = [2, 4];

        foreach ($windows as $window) {
            foreach ($periods as $p) {
                foreach ($algorithms as $algo) {
                    $candidates[] = ['digits' => $digitsFromOtp, 'period' => (int) $p, 'algorithm' => (string) $algo, 'window' => $window];
                }
            }
        }

        foreach ($candidates as $c) {
            $google2fa = new Google2FA;
            $google2fa->setWindow((int) $c['window']);
            $google2fa->setOneTimePasswordLength((int) $c['digits']);
            $google2fa->setKeyRegeneration((int) $c['period']);
            $google2fa->setAlgorithm((string) $c['algorithm']);

            if ($google2fa->verifyKey($secret, $otp)) {
                return true;
            }
        }

        return false;
    }

    private function getStoredPasswordHash(Usuario $usuario): string
    {
        $hash = (string) ($usuario->password_hash ?? '');

        if ($hash === '' && isset($usuario->senha)) {
            $hash = (string) $usuario->senha;
        }

        return $hash;
    }

    private function verifyPassword(string $password, string $storedHash): bool
    {
        if ($storedHash === '') {
            return false;
        }

        if (Hash::check($password, $storedHash)) {
            return true;
        }

        if (preg_match('/^[a-f0-9]{32}$/i', $storedHash) === 1) {
            return hash_equals(strtolower($storedHash), md5($password));
        }

        if (preg_match('/^[a-f0-9]{40}$/i', $storedHash) === 1) {
            return hash_equals(strtolower($storedHash), sha1($password));
        }

        if (preg_match('/^[a-f0-9]{64}$/i', $storedHash) === 1) {
            return hash_equals(strtolower($storedHash), hash('sha256', $password));
        }

        return false;
    }
}
