<?php
declare(strict_types=1);

namespace App\Services;

use App\DTO\RegisterDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

readonly class AuthService
{
    private const TOKEN_NAME = 'api';

    public function __construct(
        private DeviceService $deviceService,
        private UserService   $userService,
        private TokenService  $tokenService,
    )
    {
    }

    public function register(RegisterDTO $dto): string
    {
        return DB::transaction(function () use ($dto) {
            $user = $this->userService->create($dto->email, $dto->password);

            $this->deviceService->createDevice($user, $dto->deviceToken);

            return $this->tokenService->createToken($user, self::TOKEN_NAME);
        });
    }

    /**
     * @throws ValidationException
     */
    public function login(string $email, string $password): string
    {
        $user = $this->userService->findUserByEmail($email);

        if (!$user) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        if (!Hash::check($password, $user->getAuthPassword())) {
            throw ValidationException::withMessages(['password' => ['The provided credentials are incorrect.']]);
        }

        return $this->tokenService->createToken($user, self::TOKEN_NAME);
    }
}
