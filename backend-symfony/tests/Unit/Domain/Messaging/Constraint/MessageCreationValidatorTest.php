<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Messaging\Constraint;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Domain\Messaging\Constraint\MessageCreation;
use Domain\Messaging\Constraint\MessageCreationValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class MessageCreationValidatorTest extends TestCase
{
    private MockObject|Security $security;
    
    private MockObject|ConversationRepository $conversationRepository;

    private MessageCreationValidator $testedObject;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->conversationRepository = $this->createMock(ConversationRepository::class);

        $this->testedObject = new MessageCreationValidator(
            $this->security,
            $this->conversationRepository
        );
    }

    public function testWithNotUserConversation(): void
    {
        $constraint = new MessageCreation();
        $user = new User();
        $conversation = new Conversation();
        $conversation->addParticipant((new Participant())->setUser(new User()));

        $this->conversationRepository->method('find')->willReturn($conversation);
        $this->security->method('getUser')->willReturn($user);

        $context = $this->getMockExecutionContext();
        $context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->notUserConversationMessage)
            ->willReturn($this->getMockConstraintViolationBuilder());
        $this->testedObject->initialize($context);

        $this->testedObject->validate('1234', $constraint);
    }    


    public function testWithNotFoundConversation(): void
    {
        $constraint = new MessageCreation();
        $this->conversationRepository->method('find')->willReturn(null);

        $context = $this->getMockExecutionContext();
        $context->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->notFoundConversationMessage)
            ->willReturn($this->getMockConstraintViolationBuilder());
    
        $this->testedObject->initialize($context);

        $this->testedObject->validate('1234', $constraint);

    }

    public function testWithWrongConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        
        $this->testedObject->validate('test', new Email());
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