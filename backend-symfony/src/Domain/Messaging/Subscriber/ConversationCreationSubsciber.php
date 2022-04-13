<?php

declare(strict_types=1);

namespace Domain\Messaging\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Conversation;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class ConversationCreationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private HubInterface $hub,
        private RouterInterface $router,
        private SerializerInterface $serializer,
        private Security $security
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
        $conversation = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$conversation instanceof Conversation || Request::METHOD_POST !== $method) {
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();
        $otherParticipant = $conversation->getOtherParticipant($user);

        if (null === $otherParticipant) {
            return;
        }

        // $conversationTopic = $this->router->generate(
        //     name: 'api_conversations_get_collection',
        //     referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        // );

        // $userTopic = $this->router->generate(
        //     'api_users_get_item',
        //     [
        //         'id' => $otherParticipant->getUser()->getId(),
        //         'topic' => urlencode($conversationTopic),
        //     ],
        //     UrlGeneratorInterface::ABSOLUTE_URL
        // );

        // $this->hub->publish(new Update(
        //     [$conversationTopic, $userTopic],
        //     $this->serializer->serialize($conversation, 'json', ['groups' => Conversation::READ_ITEM_GROUP]),
        //     true
        // ));
    }
}
