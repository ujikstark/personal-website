<?php

declare(strict_types=1);

namespace Domain\Messaging\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Conversation;
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
        $user = $message->getSender()->getUser();
        $conversation = $message->getConversation();

        // $otherParticipantTopic = $this->router->generate(
        //     'api_users_get_item',
        //     [
        //         'id' => $otherParticipant->getUser()->getId(),
        //         'with' => 'conversations'
        //     ],
        //     UrlGeneratorInterface::ABSOLUTE_URL
        // );
        
        // $this->hub->publish(new Update(
        //     [$otherParticipantTopic],
        //     $this->serializer->serialize($message, 'json', ['groups' => Message::CREATE_GROUP]),
        //     true
        // ));

        $conversationTopic = $this->router->generate(
            name: 'api_conversations_get_item',
            parameters: ['id' => $message->getConversation()->getId()],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );

        $userTopic = $this->router->generate(
            'api_users_get_item',
            [
                'id' => $conversation->getOtherParticipant($user)->getUser()->getId(),
                'topic' => urlencode($conversationTopic),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->hub->publish(new Update(
            [$conversationTopic, $userTopic],
            $this->serializer->serialize($message, 'json', ['groups' => Message::CREATE_GROUP]),
            true
        ));

        $conversationTopic = $this->router->generate(
            name: 'api_conversations_get_collection',
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );

        $this->hub->publish(new Update(
            [$conversationTopic, $userTopic],
            $this->serializer->serialize($conversation, 'json', ['groups' => Conversation::READ_COLLECTION_GROUP]),
            true
        ));

        // send conversation publish if conversation message is null
        // get conversation and check length messages
        // if ($conversation->getMessages()->count() == 1) {
            
        //     $conversationTopic = $this->router->generate(
        //         name: 'api_conversations_get_collection',
        //         referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        //     );

        //     $this->hub->publish(new Update(
        //         [$conversationTopic, $userTopic],
        //         $this->serializer->serialize($conversation, 'json', ['groups' => Conversation::READ_ITEM_GROUP]),
        //         true
        //     ));
        // }
        
        



    }
}