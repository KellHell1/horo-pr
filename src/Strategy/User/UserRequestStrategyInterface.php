<?php

declare(strict_types=1);

namespace App\Strategy\User;

use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

interface UserRequestStrategyInterface
{
    public function execute(Request $request, UserBadge $user): JsonResponse;
}
