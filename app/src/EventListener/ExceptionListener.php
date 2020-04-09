<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Controller\BaseApiController;
use Throwable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @var BaseApiController
     */
    private $controller;
    
    /**
     * ExceptionListener constructor
     * @param BaseApiController $controller
     */
    public function __construct(BaseApiController $controller)
    {
        $this->controller = $controller;
    }
    
    /**
     * Listener
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        $response = $this->createApiResponse($exception);
        $event->setResponse($response);
    }
    
    /**
     * Creates the JsonResponse from any Exception
     * @param Exception $exception
     * @return JsonResponse
     */
    private function createApiResponse(Throwable $exception): JsonResponse
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        
        return $this->controller->response($statusCode, 'Something went wrong!');
    }
}