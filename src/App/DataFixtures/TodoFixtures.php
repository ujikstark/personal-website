<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Todo;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TodoFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        
        $faker = Factory::create();

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $todo = new Todo();

                $dateTime = $faker->dateTimeInInterval('-7 days', '+14 days');
                $dateTime->setTimestamp((int) (300 * ceil($dateTime->getTimestamp() / 300)));
                $reminder = clone $dateTime;
                $reminder = $i % 2
                    ? $reminder->sub(new \DateInterval('PT2H'))
                    : null;

                $todo
                    ->setName($faker->word())
                    ->setDescription($faker->sentence())
                    ->setDate($dateTime)
                    ->setUser($user)
                    ->setIsDone($faker->boolean())
                    ->setReminder($reminder);

                $manager->persist($todo);

            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];        
    }


}