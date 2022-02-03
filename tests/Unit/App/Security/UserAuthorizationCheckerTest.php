<?php

declare(strict_types=1);

namespace Tests\Unit\App\Security;

use App\Entity\User;
use App\Security\Exception\AuthenticationException;
use App\Security\Exception\ResourceAccessException;
use App\Security\UserAuthorizationChecker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class UserAuthorizationCheckerTest extends TestCase
{
    private MockObject|Security $security;

    private UserAuthorizationChecker $testedObject;

    protected function setUp(): void
    {
        $this->security = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->testedObject = new UserAuthorizationChecker($this->security);

    }

    public function testCheck(): void
    {
        $loggedInUser = $this->getMockBuilder(User::class)->getMock();

        /** @var User|MockObject */
        $user = $this->getMockBuilder(User::class)->getMock();

        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($loggedInUser);

        $id = 100;

        $user->method('getId')->willReturn($id);
        $loggedInUser->method('getId')->willReturn($id);

        $this->testedObject->check($user);
    }

    public function testCheckNoLoggedInUser(): void
    {
        $this->expectException(AuthenticationException::class);

        /** @var User|MockObject */
        $user = $this->getMockBuilder(User::class)->getMock();

        $this->security
            ->method('getUser')
            ->willReturn(null);
        
        $this->testedObject->check($user);
        
    }

    public function testCheckWithDifferentUser(): void
    {
        $this->expectException(ResourceAccessException::class);

        $loggedInUser = $this->getMockBuilder(User::class)->getMock();

        /** @var User|MockObject */
        $user = $this->getMockBuilder(User::class)->getMock();

        $this->security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($loggedInUser);


        $user->method('getId')->willReturn(100);
        $loggedInUser->method('getId')->willReturn(99);

        $this->testedObject->check($user);
    }

}