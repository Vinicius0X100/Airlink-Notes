<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\Verify2faRequest;
use App\Models\AirlinkNotes\LoginSession;
use App\Services\Auth\AuthService;
use App\Services\Auth\LoginSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkEmail(CheckEmailRequest $request, AuthService $authService): JsonResponse
    {
        $result = $authService->checkEmail($request->validated('email'));

        if (($result['exists'] ?? false) !== true) {
            return response()->json(['exists' => false, 'message' => 'Conta não encontrada.']);
        }

        if (($result['inactive'] ?? false) === true) {
            return response()->json(['exists' => true, 'inactive' => true, 'message' => 'Usuário inativo.'], 403);
        }

        return response()->json([
            'exists' => true,
            'inactive' => false,
            'two_factor_active' => (bool) ($result['two_factor_active'] ?? false),
            'nome' => $result['nome'] ?? null,
        ]);
    }

    public function login(LoginRequest $request, AuthService $authService, LoginSessionService $loginSessionService): JsonResponse
    {
        $result = $authService->attemptLogin(
            $request->validated('email'),
            $request->validated('password')
        );

        if (! ($result['ok'] ?? false)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        if (($result['inactive'] ?? false) === true) {
            return response()->json(['message' => 'Usuário inativo.'], 403);
        }

        if (($result['requires_2fa'] ?? false) === true) {
            return response()->json([
                'requires_2fa' => true,
                'challenge_id' => $result['challenge_id'],
            ]);
        }

        $response = response()->json([
            'token' => $result['token'],
            'user' => $result['usuario'],
        ]);

        $cookies = $loginSessionService->issueForUser($result['usuario'], $request);

        return $response
            ->withCookie($cookies['remember_cookie'])
            ->withCookie($cookies['uid_cookie']);
    }

    public function verify2fa(Verify2faRequest $request, AuthService $authService, LoginSessionService $loginSessionService): JsonResponse
    {
        $result = $authService->verify2fa(
            $request->validated('email'),
            $request->validated('otp'),
            $request->validated('challenge_id')
        );

        if (! ($result['ok'] ?? false)) {
            if (($result['inactive'] ?? false) === true) {
                return response()->json(['message' => 'Usuário inativo.'], 403);
            }

            if (($result['invalid_otp'] ?? false) === true) {
                return response()->json(['message' => 'Código inválido.'], 422);
            }

            return response()->json(['message' => 'Não foi possível validar o 2FA.'], 401);
        }

        $response = response()->json([
            'token' => $result['token'],
            'user' => $result['usuario'],
        ]);

        $cookies = $loginSessionService->issueForUser($result['usuario'], $request);

        return $response
            ->withCookie($cookies['remember_cookie'])
            ->withCookie($cookies['uid_cookie']);
    }

    public function restoreSession(Request $request, LoginSessionService $loginSessionService): JsonResponse
    {
        $usuario = $loginSessionService->restore($request);

        if (! $usuario) {
            return response()->json(['ok' => false], 401);
        }

        $token = $usuario->createToken('web')->plainTextToken;

        $cookies = $loginSessionService->issueForUser($usuario, $request);
        $loginSessionService->revokeFromRequest($request);

        return response()->json([
            'ok' => true,
            'token' => $token,
            'user' => $usuario,
        ])
            ->withCookie($cookies['remember_cookie'])
            ->withCookie($cookies['uid_cookie']);
    }

    public function logout(Request $request, LoginSessionService $loginSessionService): JsonResponse
    {
        $token = request()->user()?->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        $loginSessionService->revokeFromRequest($request);
        $forget = $loginSessionService->forgetCookies($request);

        return response()->json(['ok' => true])
            ->withCookie($forget[0])
            ->withCookie($forget[1]);
    }

    public function me(): JsonResponse
    {
        $user = request()->user();
        $userId = $user ? (int) $user->id : 0;

        $onboardingCompleted = false;
        if ($userId > 0) {
            $onboardingCompleted = LoginSession::query()
                ->where('user_id', $userId)
                ->where('onboarding_completed', true)
                ->exists();
        }

        return response()->json([
            'user' => $user,
            'onboarding_completed' => $onboardingCompleted,
        ]);
    }

    public function completeOnboarding(Request $request, LoginSessionService $loginSessionService): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $loginSessionService->markOnboardingCompleted($userId);

        return response()->json(['ok' => true]);
    }
}
