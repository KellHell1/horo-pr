<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use SensitiveParameter;

readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private TokenService $tokenService,
    ) {}

    public function getByCredentials(string $login, #[SensitiveParameter] string $password): ?User
    {
        return $this->userRepository->findOneBy([
            'login' => $login,
            'pass' => $password,
        ]);
    }

    public function createToken(User $user): ?string
    {
        return $this->tokenService->create($user);
    }
}