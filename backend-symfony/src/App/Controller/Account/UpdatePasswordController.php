<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Model\Account\UpdatePasswordDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdatePasswordController extends AbstractController
{
    public const PATH = '/account/update-password';

    public function __construct(
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function __invoke(ManagerRegistry $doctrine, UpdatePasswordDTO $data): JsonResponse
    {
        if (count($errors = $this->validator->validate($data)) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        /** @var User $user */
        $user = $this->getUser();

        $user->setPassword($this->hasher->hashPassword($user, $data->getNewPassword()));
        $user->hasBeenUpdated();

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'PASSWORD_UPDATED'], Response::HTTP_OK);
    }
}