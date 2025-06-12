<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTO\RegisterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthService $authService): JsonResponse
    {
        $token = $authService->register(new RegisterDTO(
            email: $request->email,
            password: $request->password,
            deviceToken: $request->device_token
        ));

        return response()->json([
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $token = $authService->login($request->email, $request->password);

        return response()->json([
            'token' => $token
        ]);
    }
}
