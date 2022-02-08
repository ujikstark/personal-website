<?php

declare(strict_types=1);

namespace Domain\Todo\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Todo;
use App\Entity\User;
use App\Security\UserAuthorizationChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TodoModificationSubscriber implements EventSubscriberInterface
{
    private const METHODS_ALLOWED = [
        Request::METHOD_PUT,
        Request::METHOD_DELETE,
    ];

    public function __construct(
        private UserAuthorizationChecker $userAuthorizationChecker
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function check(ViewEvent $event): void
    {
        /** @var Todo $todo */
        $todo = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($todo instanceof Todo && in_array($method, self::METHODS_ALLOWED)) {
            /** @var User $user */
            $user = $todo->getUser();
            $this->userAuthorizationChecker->check($user);
        }
    }
}
