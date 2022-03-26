<?php

declare(strict_types=1);

namespace Domain\Messaging\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Security\Exception\ResourceAccessException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ConversationItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security $security,
        private ConversationRepository $conversationRepository
    ) {   
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        $conversation = $this->conversationRepository->find($id);     
        
        if (null === $conversation) {
            return null;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        if (false === $conversation->hasUser($user)) {
            throw new ResourceAccessException(Response::HTTP_FORBIDDEN, ResourceAccessException::RESOURCE_ACCESS_EXCEPTION);
        }

        return $conversation;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Conversation::class === $resourceClass;
    }
}