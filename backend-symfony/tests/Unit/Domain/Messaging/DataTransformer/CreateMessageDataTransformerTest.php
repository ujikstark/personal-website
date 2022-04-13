<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Messaging\DataTransformer;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Domain\Messaging\DataTransformer\CreateMessageDataTransformer;
use Model\Messaging\CreateMessageDTO;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

class CreateMessageDataTransformerTest extends TestCase
{
    private MockObject|Security $security;

    private MockObject|ValidatorInterface $validator;

    private MockObject|ConversationRepository $conversationRepository;

    private CreateMessageDataTransformer $testedObject;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->conversationRepository = $this->createMock(ConversationRepository::class);

        $this->testedObject = new CreateMessageDataTransformer(
            $this->security,
            $this->validator,
            $this->conversationRepository
        );
    }

    public function testTransform(): void
    {
        $conversationId = (string) Uuid::v4();
        $object = new CreateMessageDTO();
        $object->setConversationId($conversationId);

        $this->validator->expects($this->once())->method('validate')->with($object);

        $user = new User();
        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $conversation = new Conversation();
        $conversation->addParticipant((new Participant())->setUser($user));
        $this->conversationRepository
            ->expects($this->once())
            ->method('find')
            ->with($conversationId)
            ->willReturn($conversation);

        $actual = $this->testedObject->transform($object, '', []);

        $this->assertInstanceOf(Message::class, $actual);
    }

    /** @dataProvider dataTestSupportsTransformation */
    public function testSupportsTransformation($data, string $to, array $context, bool $expected): void
    {
        $actual = $this->testedObject->supportsTransformation($data, $to, $context);

        $this->assertSame($actual, $expected);
    }

    public function dataTestSupportsTransformation(): array
    {
        return [
            'case true' => [
                'data' => new CreateMessageDTO(),
                'to' => Message::class,
                'context' => ['input' => ['class' => 'class']],
                'expected' => true,
            ],
            'case false data' => [
                'data' => new Message(),
                'to' => Message::class,
                'context' => ['input' => ['class' => 'class']],
                'expected' => false,
            ],
            'case case false to' => [
                'data' => new CreateMessageDTO(),
                'to' => User::class,
                'context' => ['input' => ['class' => 'class']],
                'expected' => false,
            ],
            'case false context' => [
                'data' => new CreateMessageDTO(),
                'to' => Message::class,
                'context' => [],
                'expected' => false,
            ],
        ];
    }
}
