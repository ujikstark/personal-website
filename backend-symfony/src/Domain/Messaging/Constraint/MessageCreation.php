<?php

declare(strict_types=1);

namespace Domain\Messaging\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class MessageCreation extends Constraint
{
    public string $notFoundConversationMessage = 'The conversation of id "{{ id }}" does not exist.';

    public string $notUserConversationMessage = 'The user "{{ userId }}" does not participate in conversation "{{ conversationId }}"';
}