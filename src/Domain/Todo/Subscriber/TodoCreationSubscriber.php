<?php

declare(strict_types=1);

namespace Domain\Todo\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Todo;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class TodoCreationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security
    ) {    
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setTodoUser', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function setTodoUser(ViewEvent $event): void
    {
        $todo = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($todo instanceof Todo && Request::METHOD_POST == $method) {
            
            /** @var User $user */
            $user = $this->security->getUser();
            $todo->setUser($user);
        }
    }

}