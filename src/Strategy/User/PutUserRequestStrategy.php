<?php

declare(strict_types=1);

namespace App\Strategy\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class PutUserRequestStrategy implements UserRequestStrategyInterface
{
    public function __construct(private UserService $userService, private UserRepository $userRepository) {}

    public function execute(Request $request, UserBadge $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $user = $this->userRepository->findOneBy([
            'login' => $data['id'],
            'pass' => $data['pass'],
        ]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        if ($user->getId() !== $id && !in_array(User::ROLE_ADMIN, $user->getRoles())) {
            return new JsonResponse(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        return $this->userService->update($id, $data);
    }
}