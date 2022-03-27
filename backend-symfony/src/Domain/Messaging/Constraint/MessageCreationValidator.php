<?php

declare(strict_types=1);

namespace Domain\Messaging\Constraint;

use App\Entity\User;
use App\Repository\ConversationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MessageCreationValidator extends ConstraintValidator
{
    public function __construct(
        private Security $security,
        private ConversationRepository $conversationRepository
    ) {        
    }

    /** @param string $value */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof MessageCreation) {
            throw new UnexpectedTypeException($constraint, MessageCreation::class);
        }

        $conversationId = $value;
        $conversation = $this->conversationRepository->find($conversationId);

        if (null === $conversation) {
            $this->context->buildViolation($constraint->notFoundConversationMessage)
                ->setParameter('{{ id }}', $conversationId)
                ->addViolation();
            return;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        if (false === $conversation->hasUser($user)) {
            $this->context->buildViolation($constraint->notUserConversationMessage)
                ->setParameter('{{ userId }}', (string) $user->getId())
                ->setParameter('{{ conversationId }}', $conversationId)
                ->addViolation();
        }
    }
}