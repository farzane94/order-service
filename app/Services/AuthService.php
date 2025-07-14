<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = Auth::login($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials): ?string
    {
        if (!$token = Auth::attempt($credentials)) {
            return null;
        }

        return $token;
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function me(): User
    {
        return Auth::user();
    }

}
