<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onExceptionEvent(ExceptionEvent $event)
    {
        $exception = $event->getException();
        if($exception instanceof NotFoundHttpException) {
            $data = [
                'message' => $exception->getMessage()
            ];
            $response = new JsonResponse($data);
            $event->setResponse($response);
        }elseif($exception instanceof \ErrorException) {
            $data = [
                'message' => $exception->getMessage()
            ];
            $response = new JsonResponse($data);
            $event->setResponse($response);
        }elseif($exception instanceof \Exception) {
            $data = [
                'message' => $exception->getMessage()
            ];
            $response = new JsonResponse($data);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onExceptionEvent',
        ];
    }
}
