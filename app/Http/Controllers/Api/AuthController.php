<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $user = User::create($request->validated());
        $result = new AuthResource($user);
        return json_ok($result, 200, 'Successfully registered user');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|min:3|max:255',
            'password' => 'required|string|min:6|max:50',
        ]);

        if (!Auth::guard('web')->attempt($credentials)) {
            return json_error(null, 401, 'Unauthorized');
        }

        // remember_token
        $user = Auth::user(); // $request->user()
        $data = AuthService::login($user, $request->remember_me);
        return json_ok($data, 200, 'Successfully logged in');
    }

    public function logout()
    {
        AuthService::logout();
        return json_ok([], 200, 'Successfully logged out');
    }

    public function me(Request $request)
    {
        $result = new AuthResource($request->user());
        return json_ok($result, 200, 'Authenticated user info.');
    }
}
