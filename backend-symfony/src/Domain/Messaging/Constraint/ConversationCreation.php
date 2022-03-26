<?php

declare(strict_types=1);

namespace Domain\Messaging\Constraint;

use Symfony\Component\Validator\Constraint;

class ConversationCreation extends Constraint
{
    public string $selfConversationMessage = 'A conversation cannot be created with yourself.';

    public string $notFoundUserMessage = 'No user was found with id "{{ id }}".';

    public string $conversationExistsMessage = 'A conversation already exists with user {{ id }}.';
}