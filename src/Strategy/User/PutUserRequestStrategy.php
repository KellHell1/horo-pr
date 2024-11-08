<?php

namespace App\Strategy\User;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};

readonly class PutUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;

        return $this->userService->update($id, $data);
    }
}