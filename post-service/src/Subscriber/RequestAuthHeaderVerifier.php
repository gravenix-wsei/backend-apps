<?php declare(strict_types=1);

namespace PostService\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestAuthHeaderVerifier implements EventSubscriberInterface
{
    public const AUTH_HEADER = 'x-my-auth-header';

    public function __construct(
        private readonly string $authValue
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'verifyHeader',
            KernelEvents::EXCEPTION => 'handleException',
        ];
    }

    public function verifyHeader(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $authHeader = $request->headers->get(self::AUTH_HEADER);

        if ($authHeader !== $this->authValue) {
            throw new AccessDeniedHttpException('Invalid Token');
        }
    }

    public function handleException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof HttpException) {
            return;
        }

        $response = new JsonResponse([
            'error' => $exception->getMessage(),
        ], $exception->getStatusCode(), $exception->getHeaders());

        $event->setResponse($response);
    }
}