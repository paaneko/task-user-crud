<?php

declare(strict_types=1);

namespace App\User\Application\Middleware;

use App\User\Application\Service\JWTServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AuthenticateUserRequestMiddleware implements EventSubscriberInterface
{
    public function __construct(private JWTServiceInterface $JWTService)
    {
    }

    public function onRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        $authorizationHeader = $request->headers->get('Authorization');

        if ($authorizationHeader !== null) {
            $jwt = $this->extractBearerToken($authorizationHeader);
            $authUser = $this->JWTService->decode($jwt);
            $request->attributes->set('authUser', $authUser);

            return;
        }

        $request->attributes->set('authUser', null);
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onRequest'];
    }

    private function extractBearerToken(string $authorizationHeader): string
    {
        if (preg_match('/^Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            return $matches[1];
        }

        throw new \DomainException('Bearer token not found');
    }
}
