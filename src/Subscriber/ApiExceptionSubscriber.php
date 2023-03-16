<?php


namespace App\Subscriber;

use App\Exception\ApiException;
use App\Helper\Util;
use JMS\Serializer\SerializerInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getThrowable();
        if (!$e instanceof ApiException) {
            return;
        }

        $response = new JsonResponse(
            $this->serializer->serialize(Util::render($e->getMessage()), 'json'),
            $e->getStatusCode(),
            [],
            true
        );
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }



}