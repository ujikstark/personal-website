<?php

declare(strict_types=1);

namespace Tests\Unit\App\Entity;

use App\Entity\Todo;
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

    public function testGetUpdateAt(): void
    {
        $updateAt = new \DateTimeImmutable();

        $user = $this->testedObject->setUpdateAt($updateAt);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($updateAt, $user->getUpdateAt());
    }

    public function testGetName(): void
    {
        $name = 'john';

        $user = $this->testedObject->setName($name);
        
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($name, $user->getName());
    }

    public function testHasBeenUpdated(): void
    {
        $now = new \DateTimeImmutable();
        $this->testedObject->hasBeenUpdated();

        $this->assertEquals($now->format('d/m/Y H:i'), $this->testedObject->getUpdateAt()->format('d/m/Y H:i'));
    }

    public function testAddTodo(): void
    {
        $todo = new Todo();

        $user = $this->testedObject->addTodo($todo);

        $this->assertInstanceOf(User::class, $user);
        $this->assertContains($todo, $user->getTodos());
    }

    public function testRemoveTodo(): void
    {
        $todo = new Todo();
        $user = $this->testedObject
            ->addTodo($todo)
            ->removeTodo($todo)
        ;

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotContains($todo, $user->getTodos());
    }

    public function testEraseCredentials(): void
    {
        $this->assertNull($this->testedObject->eraseCredentials());
    }
}