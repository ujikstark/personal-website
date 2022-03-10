<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Security\Exception\AuthenticationException;
use App\Security\Exception\ResourceAccessException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class UserAuthorizationChecker
{
    public function __construct(
        private Security $security
    ) {
        
    }

    public function check(User $resourceUser): void
    {
        /** @var User|null $loggedInUser */
        $loggedInUser = $this->security->getUser();

        if (null === $loggedInUser) {
            throw new AuthenticationException(Response::HTTP_UNAUTHORIZED, AuthenticationException::AUTHENTICATION_EXCEPTION);
        }

        if ($loggedInUser->getId() !== $resourceUser->getId()) {
            throw new ResourceAccessException(Response::HTTP_UNAUTHORIZED, ResourceAccessException::RESOURCE_ACCESS_EXCEPTION);
        }
    }

}