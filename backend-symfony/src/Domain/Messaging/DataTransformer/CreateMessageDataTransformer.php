<?php

declare(strict_types=1);

namespace Domain\Messaging\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Model\Messaging\CreateMessageDTO;
use Symfony\Component\Security\Core\Security;

class CreateMessageDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private Security $security,
        private ValidatorInterface $validator,
        private ConversationRepository $conversationRepository
    ) {      
    }
    
    /** @param CreateMessageDTO $object */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        /** @var User $user */
        $user = $this->security->getUser();
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($object->getConversationId());
        /** @var Participant $participant */
        $participant = $conversation->getParticipantByUser($user);

        return (new Message())
            ->setContent($object->getContent())
            ->setConversation($conversation)
            ->setSender($participant);
    }   


    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return false === $data instanceof Message
            && Message::class == $to
            && null !== ($context['input']['class'] ?? null);
    }


}