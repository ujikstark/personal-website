<?php

declare(stricy_types=1);

namespace Tests\Unit\Domain\Messaging\Constraint;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\UserRepository;
use Domain\Messaging\Constraint\ConversationCreation;
use Domain\Messaging\Constraint\ConversationCreationValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ConversationCreationValidatorTest extends TestCase
{
    private MockObject|Security $security;

    private MockObject|UserRepository $userRepository;

    private ConversationCreationValidator $testedObject;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->testedObject = new ConversationCreationValidator(
            $this->security,
            $this->userRepository
        );   
    }

    public function testValidateWithSelfConversation(): void
    {
        $constraint = new ConversationCreation();
        $value = (string) Uuid::v4();
        $user = new User();
        $user->setId(Uuid::fromString($value));

        $this->security->expects($this->once())->method('getUser')->willReturn($user);

        $context = $this->getMockExecutionContext();
        $context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->selfConversationMessage)
            ->willReturn($this->getMockConstraintViolationBuilder());
        
        $this->testedObject->initialize($context);

        $this->testedObject->validate($value, $constraint);
    }

    public function testValidateWithNotFoundUser(): void
    {
        $constraint = new ConversationCreation();
        $value = (string) Uuid::v4();
        $user = new User();

        $this->security->expects($this->once())->method('getUser')->willReturn($user);
        $this->userRepository->expects($this->once())->method('find')->willReturn(null);

        $context = $this->getMockExecutionContext();
        $context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->notFoundUserMessage)
            ->willReturn($this->getMockConstraintViolationBuilder());
        $this->testedObject->initialize($context);

        $this->testedObject->validate($value, $constraint);
    }

    public function testValidateWithExistingConversation(): void
    {
        $constraint = new ConversationCreation();
        $value = (string) Uuid::v4();

        $user = new User();
        $participant = new Participant();
        $user->addParticipant($participant);

        $otherUser = new User();
        $otherParticipant = new Participant();
        $otherUser->setId(Uuid::fromString($value));
        $otherUser->addParticipant($otherParticipant);

        (new Conversation())
            ->addParticipant($participant)
            ->addParticipant($otherParticipant);

        $this->security->expects($this->once())->method('getUser')->willReturn($user);
        $this->userRepository->expects($this->once())->method('find')->willReturn($otherUser);

        $context = $this->getMockExecutionContext();
        $context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->conversationExistsMessage)
            ->willReturn($this->getMockConstraintViolationBuilder());
        $this->testedObject->initialize($context);

        $this->testedObject->validate($value, $constraint);
    }
    
    private function getMockExecutionContext(): MockObject|ExecutionContextInterface
    {
        return $this->getMockBuilder(ExecutionContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMockConstraintViolationBuilder(): MockObject|ConstraintViolationBuilderInterface
    {
        $constraintViolationBuilder = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        $constraintViolationBuilder
            ->expects($this->any())
            ->method('setParameter')
            ->willReturn($constraintViolationBuilder);
        $constraintViolationBuilder
            ->expects($this->once())
            ->method('addViolation');

        return $constraintViolationBuilder;
    }

}