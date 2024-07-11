<?php

namespace App\Services;

class AuthService
{
    public static function login($user, $rememberMe = false): array
    {
        $result = $user->createToken('Personal Access Token');
        $token = $result->token;

        if ($rememberMe) {
            $token->expires_at = now()->addWeeks(1);
        }

        $token->save();

        return [
            'token_type' => 'Bearer',
            'access_token' => $result->accessToken,
            'expires_at' => now()->parse($token->expires_at)->toDateTimeString(),
        ];
    }

    public static function logout()
    {
        request()->user()?->token()?->revoke();
        auth('web')->logout();
    }
}