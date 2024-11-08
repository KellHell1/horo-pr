<?php

namespace App\Strategy\User;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};

readonly class GetUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(Request $request): JsonResponse
    {
        $id = $request->query->get('id');

        return $this->userService->get($id);
    }
}