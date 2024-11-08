<?php

declare(strict_types=1);

namespace App\Controller;

use App\Strategy\User\{
    DeleteUserRequestStrategy,
    GetUserRequestStrategy,
    PostUserRequestStrategy,
    PutUserRequestStrategy,
};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    private array $strategies;

    public function __construct(
        PostUserRequestStrategy $postStrategy,
        GetUserRequestStrategy $getStrategy,
        PutUserRequestStrategy $putStrategy,
        DeleteUserRequestStrategy $deleteStrategy,
    ) {
        $this->strategies = [
            'POST' => $postStrategy,
            'GET' => $getStrategy,
            'PUT' => $putStrategy,
            'DELETE' => $deleteStrategy,
        ];
    }

    #[Route('/v1/api/users', methods: ['POST', 'GET', 'PUT', 'DELETE'])]
    public function users(Request $request): JsonResponse
    {
        $method = $request->getMethod();
        $user = $this->getUser();

        if (!isset($this->strategies[$method])) {
            return new JsonResponse(['error' => 'Method not allowed'], 405);
        }

        $strategy = $this->strategies[$method];

        return $strategy->execute($request, $user);
    }
}
