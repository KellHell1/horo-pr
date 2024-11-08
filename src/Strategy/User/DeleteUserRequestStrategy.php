<?php

namespace App\Strategy\User;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class DeleteUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(Request $request): JsonResponse
    {
        $id = $request->query->get('id');

        return $this->userService->delete($id);
    }
}