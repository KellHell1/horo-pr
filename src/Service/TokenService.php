<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class TokenService
{
    private const ID_KEY = 'id';
    private const EXPIRE_KEY = 'expires';
    private const TTL = 24 * 3600;

    public function __construct(#[Autowire(env: 'APP_SECRET')] private readonly string $secretKey) {}

    public function create(User $user): string
    {
        if ($user->getId() === null) {
            throw new \InvalidArgumentException('User must have an id.');
        }

        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR);
        $payload = json_encode([
            self::ID_KEY => $user->getId(),
            self::EXPIRE_KEY => time() + self::TTL,
        ], JSON_THROW_ON_ERROR);

        $base64UrlHeader = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64UrlPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    public function getUserId(string $token): ?int
    {
        $userId = null;

        $parts = explode('.', $token);
        if (count($parts) === 3) {
            if ($this->validate($token)) {
                $payload = json_decode(base64_decode($parts[1]), true, 512, JSON_THROW_ON_ERROR);

                $userId = $payload[self::ID_KEY] ?? null;
            }
        }

        return $userId;
    }

    public function validate(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;

        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignatureCheck = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        if ($base64UrlSignature !== $base64UrlSignatureCheck) {
            return false;
        }

        $payload = json_decode(base64_decode($base64UrlPayload), true, 512, JSON_THROW_ON_ERROR);

        return isset($payload[self::EXPIRE_KEY]) && ($payload[self::EXPIRE_KEY] > time());
    }
}
