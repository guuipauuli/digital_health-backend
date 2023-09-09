<?php

namespace App\Handler;

use App\Exception\RegistryNotFoundException;
use App\Exception\ValidationException;
use App\Helper\MessagesHelper;
use App\Helper\ResponseHelper;
use App\Kernel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\DBAL\Exception AS DBALException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;

class ExceptionHandler implements EventSubscriberInterface
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleInsufficientAuthenticationException', -1],
                ['handleAccessDeniedException', -1],
                ['handleBadRequestHttpException', -1],
                ['handleDBALException', -1],
                ['handleValidationException', -1],
                ['handleRegistryNotFoundException', -1],
                ['handleGenericException', -1],
                ['handle404Exception', 0]
            ]
        ];
    }

    public function handleInsufficientAuthenticationException(ExceptionEvent $event)
    {
        if ($event->getThrowable()
                ->getPrevious() instanceof InsufficientAuthenticationException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_UNAUTHORIZED));
        }
    }

    public function handleAccessDeniedException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof AccessDeniedHttpException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_FORBIDDEN));
        }
    }

    public function handleBadRequestHttpException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof BadRequestHttpException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_BAD_REQUEST));
        }
    }

    public function handleValidationException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof ValidationException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_BAD_REQUEST));
        }
    }

    public function handleRegistryNotFoundException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof RegistryNotFoundException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_BAD_REQUEST));
        }
    }

    public function handleDBALException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof DBALException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $event->setResponse($this->json(
            $event, Response::HTTP_INTERNAL_SERVER_ERROR));
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $event->setResponse($this->json($event, Response::HTTP_NOT_FOUND));
        }
    }

    private function json(
        ExceptionEvent $event,
        int $statusCode
    )
    {
        if(!in_array($statusCode, [Response::HTTP_INTERNAL_SERVER_ERROR, Response::HTTP_NOT_FOUND])) {
            return new JsonResponse(new ResponseHelper(
                $statusCode, $event->getThrowable()->getMessage()), $statusCode);
        }

        $data = [];
        if($statusCode == Response::HTTP_NOT_FOUND || $this->kernel->isDebug()) {
            $data['error'] = $event->getThrowable()->getMessage();
        }

        return new JsonResponse(new ResponseHelper(
            $statusCode, MessagesHelper::INTERNAL_SERVER_ERROR, $data), $statusCode);
    }
}
