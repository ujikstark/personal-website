<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{
    public const DEFAULT_EMAIL = 'test@admin.com';
    public const DEFAULT_PASSWORD = 'test1';
    public const DEFAULT_NAME = 'test1';
    public const DEFAULT_UUID = '20354d7a-e4fe-47af-8ff6-187bca92f3f9';

    private $hasher;

    public function __construct(
        UserPasswordHasherInterface $hasher
    ) {
        $this->hasher = $hasher;

    }

    public function load(ObjectManager $manager): void
    {
        $defaultUser = new User();

        $password = $this->hasher->hashPassword($defaultUser, self::DEFAULT_PASSWORD);
        $defaultUser
            ->setEmail(self::DEFAULT_EMAIL)
            ->setName(self::DEFAULT_NAME)
            ->setPassword($password)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdateAt(new DateTimeImmutable())
            ->setId(Uuid::fromString(self::DEFAULT_UUID));

        $manager->persist($defaultUser);
        
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            
            $password = $this->hasher->hashPassword($user, self::DEFAULT_PASSWORD);

            $user
                ->setEmail($faker->email())
                ->setName($faker->name())
                ->setPassword($password)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable());
                
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
