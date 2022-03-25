<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private ConversationRepository $conversationRepository,
        private UserRepository $userRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {

        $conversations = $this->conversationRepository->findAll();
        $users = $this->userRepository->findAll();
        /** @var User $defaultUser */
        $defaultUser = $this->userRepository->find(UserFixtures::DEFAULT_UUID);

        $users = array_values(array_filter($users, function ($user) {
            return !in_array((string) $user->getId(), [UserFixtures::DEFAULT_UUID, UserFixtures::USER_WITH_NO_CONVERSATION]);
        }));

        foreach ($conversations as $key => $conversation) {
            $defaultParticipant = (new Participant())
                ->setConversation($conversation)
                ->setUser($defaultUser);
            $manager->persist($defaultParticipant);

            $participant = (new Participant())
                ->setConversation($conversation)
                ->setUser($users[$key]);
            $manager->persist($participant);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ConversationFixtures::class,
        ];
    }
}
