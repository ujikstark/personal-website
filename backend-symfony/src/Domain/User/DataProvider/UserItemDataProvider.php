<?php

declare(strict_types=1);

namespace Domain\User\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository
    ){
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        $user = $this->userRepository->find($id);

        if (null === $user) {
            return null;
        }

        return $user;
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return User::class === $resourceClass;
    }
}