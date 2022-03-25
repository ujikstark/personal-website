<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Conversation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ConversationFixtures extends Fixture
{
    public const CONVERSATION_UUID = '4abd874c-b113-4ddc-866e-1cdc99526fcd';

    public function load(ObjectManager $manager): void
    {
        for ($c = 0; $c < 9; ++$c) {
            $conversation = new Conversation();

            if (8 === $c) {
                $conversation->setId(Uuid::fromString(ConversationFixtures::CONVERSATION_UUID));
            }

            $manager->persist($conversation);
        }

        $manager->flush();
    }
}
