<?php

namespace App\Handlers;

use App\Exception\RegistryNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handle404Exception', 0]
            ]
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException || $event->getThrowable() instanceof RegistryNotFoundException) {
            $event->setResponse($this->json(
                $event, Response::HTTP_NOT_FOUND, $event->getThrowable()->getMessage()));
        }
    }

    private function json(
        ExceptionEvent $event,
        int $statusCode,
        string $message
    )
    {
        $data['error'] = null;
        if(!in_array($statusCode, [Response::HTTP_INTERNAL_SERVER_ERROR, Response::HTTP_NOT_FOUND])) {
            $data['error'] = $event?->getThrowable()->getMessage();
        }
        $data['message'] = $message;

        return new JsonResponse($data, $statusCode);
    }
}
