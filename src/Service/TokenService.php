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

        $header = $this->encodeHeader();
        $payload = $this->encodePayload($user);

        $signature = $this->generateSignature($header, $payload);

        return $header . '.' . $payload . '.' . $signature;
    }

    public function getUserId(string $token): ?int
    {
        $userId = null;

        if ($this->validate($token)) {
            $parts = explode('.', $token);
            $payload = json_decode(base64_decode($parts[1]), true, 512, JSON_THROW_ON_ERROR);

            $userId = $payload[self::ID_KEY] ?? null;
        }

        return $userId;
    }

    public function validate(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;

        if (!$this->isValidSignature($base64UrlHeader, $base64UrlPayload, $base64UrlSignature)) {
            return false;
        }

        return $this->isNotExpired($base64UrlPayload);
    }

    private function encodeHeader(): string
    {
        return rtrim(strtr(base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR)), '+/', '-_'), '=');
    }

    private function encodePayload(User $user): string
    {
        $payload = json_encode([
            self::ID_KEY => $user->getId(),
            self::EXPIRE_KEY => time() + self::TTL,
        ], JSON_THROW_ON_ERROR);

        return rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
    }

    private function generateSignature(string $header, string $payload): string
    {
        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secretKey, true);

        return rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
    }

    private function isValidSignature(string $header, string $payload, string $signature): bool
    {
        $expectedSignature = $this->generateSignature($header, $payload);

        return $signature === $expectedSignature;
    }

    private function isNotExpired(string $payload): bool
    {
        $decodedPayload = json_decode(base64_decode($payload), true, 512, JSON_THROW_ON_ERROR);

        return isset($decodedPayload[self::EXPIRE_KEY]) && ($decodedPayload[self::EXPIRE_KEY] > time());
    }
}
