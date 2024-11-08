<?php

namespace App\Strategy\User;

use Symfony\Component\HttpFoundation\{JsonResponse, Request};

interface UserRequestStrategyInterface
{
    public function execute(Request $request): JsonResponse;
}