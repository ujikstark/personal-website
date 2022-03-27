<?php

declare(strict_types=1);

namespace Model\Messaging;

use Domain\Messaging\Constraint as MessagingConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class CreateMessageDTO
{
    #[Assert\Uuid]
    #[MessagingConstraint\MessageCreation]
    private string $conversationId = '';

    #[Assert\NotBlank]
    private string $content = '';

    public function getConversationId(): string
    {
        return $this->conversationId;
    }

    public function setConversation(string $conversationId): void
    {
        $this->conversationId = $conversationId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

}