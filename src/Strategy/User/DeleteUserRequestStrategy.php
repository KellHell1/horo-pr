<?php

declare(strict_types=1);

namespace App\Strategy\User;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class DeleteUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(Request $request, UserBadge $user): JsonResponse
    {
        $id = (int) $request->query->get('id');

        if (!in_array(User::ROLE_ADMIN, $user->getRoles())) {
            return new JsonResponse(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        return $this->userService->delete($id);
    }
}
