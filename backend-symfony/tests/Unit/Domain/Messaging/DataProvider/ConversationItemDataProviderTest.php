<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Messaging\DataProvider;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Security\Exception\ResourceAccessException;
use Domain\Messaging\DataProvider\ConversationItemDataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ConversationItemDataProviderTest extends TestCase
{
    private MockObject|Security $security;

    private MockObject|ConversationRepository $conversationRepository;

    private ConversationItemDataProvider $testedObject;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->conversationRepository = $this->createMock(ConversationRepository::class);

        $this->testedObject = new ConversationItemDataProvider(
            $this->security,
            $this->conversationRepository
        );
    }

    public function testGetItem(): void
    {
        $user = new User();
        $conversation = $this->getMockBuilder(Conversation::class)->getMock();
        $conversation->method('hasUser')->with($user)->willReturn(true);

        $this->conversationRepository->method('find')->willReturn($conversation);
        $this->security->method('getUser')->willReturn($user);
        
        $actual = $this->testedObject->getItem('class', 'id');

        $this->assertEquals($conversation, $actual);
    }

    public function testGetItemNotFound(): void
    {
        $this->conversationRepository->method('find')->willReturn(null);
        $actual = $this->testedObject->getItem('class', 'id');

        $this->assertNull($actual);
    }

    public function testGetItemWithUnauthorizedResource(): void
    {
        $this->expectException(ResourceAccessException::class);

        $user = new User();
        $conversation = $this->getMockBuilder(Conversation::class)->getMock();
        $conversation->method('hasUser')->with($user)->willReturn(false);

        $this->conversationRepository->method('find')->willReturn($conversation);
        $this->security->method('getUser')->willReturn($user);

        $this->testedObject->getItem('class', 'id');
    }



}