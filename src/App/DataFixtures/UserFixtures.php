<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const DEFAULT_EMAIL = 'admin@admin.com';
    public const DEFAULT_PASSWORD = 'admin';

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
            ->setPassword($password)
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdateAt(new DateTimeImmutable());

        $manager->persist($defaultUser);
        
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            
            $password = $this->hasher->hashPassword($user, self::DEFAULT_PASSWORD);

            $user
                ->setEmail($faker->email())
                ->setPassword($password)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable());
                
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
