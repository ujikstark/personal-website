<?php

declare(strict_types=1);

namespace Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $testedObject;

    protected function setUp(): void
    {
        $this->testedObject = new User();
    }

    public function testGetEmail(): void
    {
        $email = 'admin@admin.com';

        $user = $this->testedObject->setEmail($email);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $this->testedObject->getEmail());
    }

    public function testGetRoles(): void
    {
        $roleAdmin = ['ROLE_ADMIN'];

        $user = $this->testedObject->setRoles($roleAdmin);

        $this->assertInstanceOf(User::class, $user);
        $this->assertContains('ROLE_USER', $this->testedObject->getRoles());
        $this->assertContains('ROLE_ADMIN', $this->testedObject->getRoles());
    }

    public function testGetPassword(): void
    {
        $password = 'password';

        $user = $this->testedObject->setPassword($password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($password, $this->testedObject->getPassword());
    }
}