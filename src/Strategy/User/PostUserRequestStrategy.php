<?php

declare(strict_types=1);

namespace App\Strategy\User;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class PostUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService) {}

    public function execute(Request $request, UserBadge $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($user->getId() !== $data['id'] && !in_array(User::ROLE_ADMIN, $user->getRoles())) {
            return new JsonResponse(['error' => 'Access denied'], JsonResponse::HTTP_FORBIDDEN);
        }

        return $this->userService->create($data);
    }
}
