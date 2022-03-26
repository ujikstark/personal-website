<?php

declare(strict_types=1);

namespace Domain\Messaging\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class ConversationCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security $security
    ) {    
    }

    public function getCollection(string $resourceClass, ?string $operationName = null)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $conversations = [];

        foreach ($user->getParticipants() as $participant) {
            $conversations[] = $participant->getConversation();
        }

        return $conversations;
        
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return Conversation::class === $resourceClass;
    }
}