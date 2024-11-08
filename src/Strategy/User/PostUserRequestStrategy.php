<?php

namespace App\Strategy\User;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class PostUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        return $this->userService->create($data);
    }
}