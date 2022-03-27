<?php

declare(strict_types=1);

namespace Domain\Messaging\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\UserRepository;
use Model\Messaging\CreateConversationDTO;
use Symfony\Component\Security\Core\Security;

class CreateConversationDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private Security $security,
        private ValidatorInterface $validator,
        private UserRepository $userRepository
    ) {   
    }


    /** @param CreateConversationDTO $object */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);       
        $conversation = new Conversation();

        /** @var User $user */
        $user = $this->security->getUser();
        /** @var User $otherUser */
        $otherUser = $this->userRepository->find($object->getUserId());
        
        $conversation->addParticipant((new Participant())->setUser($user));
        $conversation->addParticipant((new Participant())->setUser($otherUser));

        return $conversation;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return false === $data instanceof Conversation
            && Conversation::class === $to
            && null !== ($context['input']['class'] ?? null);
    }
}