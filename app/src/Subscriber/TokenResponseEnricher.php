<?php declare(strict_types=1);

namespace App\Subscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TokenResponseEnricher implements EventSubscriberInterface
{
    public function __construct(private readonly JWTTokenManagerInterface $tokenManager)
    {
    }

    public static function getSubscribedEvents()
    {
        return [Events::AUTHENTICATION_SUCCESS => 'onAuthorizationSuccess'];
    }

    public function onAuthorizationSuccess(AuthenticationSuccessEvent $event): void
    {
        $strToken = $event->getData()['token'] ?? null;
        /** @var TokenInterface $token */
        $token = $this->tokenManager->parse($strToken);
        $event->setData(\array_merge($event->getData(), ['expiresIn' => $token['exp']]));
    }
}