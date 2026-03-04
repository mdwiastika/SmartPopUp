<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);

            $user->assignRole('user');

            $validated['password'] = $request['password'];

            $token = Auth::attempt($validated);

            if (! $token) {
                return new UserCollection(false, 'Invalid email or password', []);
            }

            return new UserCollection(true, 'User registered successfully', $this->respondWithToken($token));
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to register user', []);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            if (! $token = Auth::attempt($validated)) {
                return new UserCollection(false, 'Invalid email or password', []);
            }

            return UserCollection::make(true, 'User logged in successfully', $this->respondWithToken($token));
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to login user', []);
        }
    }

    public function refresh()
    {
        try {
            $token = Auth::refresh();
            return UserCollection::make(true, 'Token refreshed successfully', $this->respondWithToken($token));
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to refresh token', []);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return new UserCollection(true, 'User logged out successfully', []);
        } catch (\Throwable $th) {
            return new UserCollection(false, 'Failed to logout user', []);
        }
    }


    protected function respondWithToken($token)
    {
        return [
            'access_token' => 'Bearer ' . $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
}
