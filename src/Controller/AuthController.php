<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\{AuthService, TokenService};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route(path: 'v1/api/login', methods: ['POST'])]
    public function login(AuthService $authService, TokenService $tokenService, Request $request): JsonResponse
    {
        $user = $authService->getByCredentials(
            $request->query->get('login'),
            $request->query->get('pass'),
        );

        if ($user === null) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse(['token' => $tokenService->create($user)]);
    }
}