<?php

namespace App\Http\Controllers\Auth;

use App\Enums\HttpStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return ApiResponse::success([
            'user' => new UserResource($result['user']),
            'token' => $result['token']
        ], HttpStatus::CREATED->value);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return response()->json(['message' => __('messages.auth.unauthorized')], HttpStatus::UNAUTHORIZED->value);
        }

        return ApiResponse::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ], HttpStatus::OK->value);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => __('messages.auth.logout_success')]);
    }

}
