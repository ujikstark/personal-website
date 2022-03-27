<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Messaging\DataProvider;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use Domain\Messaging\DataProvider\ConversationCollectionDataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ConversationCollectionDataProviderTest extends TestCase
{
    private MockObject|Security $security;

    private ConversationCollectionDataProvider $testedObject;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);

        $this->testedObject = new ConversationCollectionDataProvider(
            $this->security
        );
    }

    public function testGetCollection(): void
    {
        $user = new User();
        $conversation = new Conversation();
        $participant = new Participant();
        $user->addParticipant($participant);
        $conversation->addParticipant($participant);

        $this->security->method('getUser')->willReturn($user);
        $actual = $this->testedObject->getCollection('class');
        $this->assertCount(1, $actual);
        $this->assertEquals([$conversation], $actual);
    }
}