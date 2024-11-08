<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = [
            'status' => 'error',
            'message' => $exception->getMessage(),
            'code' => method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500,
        ];

        $event->setResponse(new JsonResponse($response, $response['code']));
    }
}
