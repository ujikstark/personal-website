<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Message;
use App\Repository\ParticipantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private ParticipantRepository $participantRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {

        $participants = $this->participantRepository->findAll();
        $faker = Factory::create();

        foreach ($participants as $participant) {
            for ($m = 0; $m < 5; ++$m) {
                $message = (new Message())
                    ->setSender($participant)
                    ->setConversation($participant->getConversation())
                    ->setContent($faker->sentence())
                    ->setDate(\DateTimeImmutable::createFromMutable($faker->dateTimeInInterval('-7 days', '+7 days')))
                ;

                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ParticipantFixtures::class,
        ];
    }
}
