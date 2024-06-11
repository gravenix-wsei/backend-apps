<?php

namespace PostService\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestJsonResolverSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->headers->get('Content-Type') !== 'application/json' || empty($request->getContent())) {
            return;
        }

        $jsonPayload = \json_decode($request->getContent(), true, \JSON_THROW_ON_ERROR);
        if (!$jsonPayload) {
            throw new BadRequestHttpException('Invalid payload');
        }
        foreach ($jsonPayload as $key => $parameter) {
            $request->request->set($key, $parameter);
        }
    }
}
