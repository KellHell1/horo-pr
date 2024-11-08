<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private ValidatorInterface $validator,
    ) {}

    public function create(array $data): JsonResponse
    {
        if (!isset($data['id'], $data['login'], $data['phone'], $data['pass'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        $existingUser = $this->userRepository->findOneBy(['login' => $data['login'], 'pass' => $data['pass']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'User with this login and pass already exists'], 400);
        }

        $user = new User($data['login'], null, $data);
        $user->setId($data['id']);
        $user->setLogin($data['login']);
        $user->setPhone($data['phone']);
        $user->setPass($data['pass']);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], 400);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
            'pass' => $user->getPass(),
        ], 201);
    }

    public function get(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse([
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
            'pass' => $user->getPass(),
        ]);
    }

    public function update(int $id, array $data): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        if (isset($data['login'])) {
            $user->setLogin($data['login']);
        }
        if (isset($data['phone'])) {
            $user->setPhone($data['phone']);
        }
        if (isset($data['pass'])) {
            $user->setPass($data['pass']);
        }

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], 400);
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
            'pass' => $user->getPass(),
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse([], 204);
    }
}
