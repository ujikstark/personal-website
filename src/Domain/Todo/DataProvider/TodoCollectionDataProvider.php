<?php

declare(strict_types=1);

namespace Domain\Todo\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Todo;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Security;

final class TodoCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Todo::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null): Collection
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $user->getTodos();
    }
}
