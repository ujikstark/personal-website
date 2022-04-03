<?php

declare(strict_types=1);

namespace Domain\Messaging\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Message;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MessageCreationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private HubInterface $hub,
        private RouterInterface $router,
        private SerializerInterface $serializer
    ) {    
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['dispatchUpdate', EventPriorities::POST_WRITE],
        ];        
    }

    public function dispatchUpdate(ViewEvent $event): void
    {
        $message = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        
        
        if (!$message instanceof Message || Request::METHOD_POST !== $method) {
            return;
        }


        
        $this->hub->publish(new Update(
            $this->router->generate(
                'api_conversations_get_item',
                ['id' => (string) $message->getConversation()->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL, 
            ),
            json_encode(['message' => 'success'])
        ));




    }
}