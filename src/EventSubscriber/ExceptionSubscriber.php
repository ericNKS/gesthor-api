<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        // Determine status code
        $statusCode = 500;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }
        
        // Create response data
        $responseData = [
            'status' => $statusCode,
            'error' => true,
            'message' => $exception->getMessage()
        ];
        
        // Add more specific info for common errors
        if ($exception instanceof NotFoundHttpException) {
            $responseData['message'] = 'Resource not found';
            $responseData['details'] = $exception->getMessage() ?: 'The requested resource was not found on this server';
        }
        
        // Create and configure JSON response
        $response = new JsonResponse($responseData, $statusCode);
        
        // Set the response on the event
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}