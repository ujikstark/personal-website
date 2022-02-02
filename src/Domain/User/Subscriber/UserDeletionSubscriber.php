<?php

declare(strict_types=1);

namespace Domain\User\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Security\UserAuthorizationChecker;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserDeletionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserAuthorizationChecker $userAuthorizationChecker
    ) {
        
    }

    /**
     * @@return array<array>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE],
        ];
    }
    
    public function check(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        
        if ($user instanceof User && Request::METHOD_DELETE === $method) {
            $this->userAuthorizationChecker->check($user);
        }
    }

}