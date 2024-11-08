<?php

declare(strict_types=1);

namespace App\Security;

use App\Service\TokenService;
use SensitiveParameter;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Throwable;

readonly class AccessHandler implements AccessTokenHandlerInterface
{
    public function __construct(private TokenService $tokenService) {}

    public function getUserBadgeFrom(#[SensitiveParameter] string $accessToken): UserBadge
    {
        try {
            $isTokenValid = $this->tokenService->validate($accessToken);
            $userId = $this->tokenService->getUserId($accessToken);

            if (!$isTokenValid || !$userId) {
                throw new BadRequestException('Token is invalid.');
            }

            return new UserBadge((string) $userId);
        } catch (Throwable $e) {
            throw new BadCredentialsException('Invalid credentials.');
        }
    }
}
