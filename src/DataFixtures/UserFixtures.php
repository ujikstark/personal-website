<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public const DEFAULT_EMAIL = 'admin@admin.com';
    public const DEFAULT_PASSWORD = 'admin';

    public function load(ObjectManager $manager): void
    {
        $defaultUser = new User();

        $defaultUser
            ->setEmail(self::DEFAULT_EMAIL)
            ->setPassword(self::DEFAULT_PASSWORD);

        $manager->persist($defaultUser);
        
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            
            $password = $this->hasher->hashPassword($user, self::DEFAULT_PASSWORD);

            $user
                ->setEmail($faker->email())
                ->setPassword($password);
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
