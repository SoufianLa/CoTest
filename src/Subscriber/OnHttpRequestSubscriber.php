<?php


namespace App\Subscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class OnHttpRequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }
    public function onKernelRequest(RequestEvent $event)
    {
        $this->checkApiSecret($event);
    }

    private function checkApiSecret(RequestEvent $event): void
    {
        $authorizedRoutes = ["app.swagger_ui", "app.swagger"];
        if (!in_array($event->getRequest()->get('_route'), $authorizedRoutes))
        {
            if ($this->checkRoute($event))
                throw new ApiException(Response::HTTP_FORBIDDEN, "ACCESS_NOT_GRANTED");
        }
    }

    public function checkRoute(RequestEvent $event)
    {
        $secret = $event->getRequest()->headers->get('X-APP-SECRET');
        $pathInfo = $event->getRequest()->getPathInfo();
        if (preg_match("/^(\/api\/*)/", $pathInfo) and $_SERVER["APP_SECRET"] != $secret) return true; else return false;
    }

}