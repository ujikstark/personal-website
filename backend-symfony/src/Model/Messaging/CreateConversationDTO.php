<?php

declare(strict_types=1);

namespace Model\Messaging;

use Symfony\Component\Validator\Constraints as Assert;
use Domain\Messaging\Constraint as MessagingConstraint;

class CreateConversationDTO
{
    #[Assert\Uuid]
    #[MessagingConstraint\ConversationCreation]
    private string $userId = '';

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }
}