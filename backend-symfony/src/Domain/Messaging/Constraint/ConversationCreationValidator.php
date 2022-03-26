<?php

declare(strict_types=1);

namespace Domain\Messaging\Constraint;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ConversationCreationValidator extends ConstraintValidator
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
    ) {
    }

    /** @param string $value */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConversationCreation) {
            throw new UnexpectedTypeException($constraint, ConversationCreation::class);
        }

        /** @var User $user */
        $user = $this->security->getUser();

        if ((string) $user->getId() === $value) {
            $this->context->buildViolation($constraint->selfConversationMessage)
                ->addViolation();

            return;
        }

        $otherUser = $this->userRepository->find($value);

        if (null === $otherUser) {
            $this->context->buildViolation($constraint->notFoundUserMessage)
                ->setParameter('{{ id }}', $value)
                ->addViolation();

            return;
        }

        foreach ($user->getParticipants() as $participant) {
            if ($participant->getConversation()->hasUser((string) $otherUser->getId())) {
                $this->context->buildViolation($constraint->conversationExistsMessage)
                    ->setParameter('{{ id }}', $value)
                    ->addViolation();
            }
        }
    }
}
