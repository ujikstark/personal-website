<?php

declare(strict_type=1);

namespace App\Controller\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Model\Security\ResetPasswordDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as ValidatorValidatorInterface;

class ResetPasswordController extends AbstractController
{
    public const PATH = '/security/reset-password';

    public function __construct(
        private ValidatorValidatorInterface $validator,
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    ) {   
    }

    public function __invoke(ResetPasswordDTO $data): JsonResponse
    {
        if (count($errors = $this->validator->validate($data)) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findOneBy([
            'resetPasswordToken' => $data->token
        ]);

        if (null === $user || $user->isResetPasswordTokenExpired()) {
            return $this->json(['message' => 'INVALID TOKEN'], Response::HTTP_UNAUTHORIZED);
        }

        $user->setPassword($this->hasher->hashPassword($user, $data->password));
        $user->eraseResetPasswordData();
        $user->hasBeenUpdated();

        $this->em->persist($user);
        $this->em->flush();

        return $this->json(['email' => $user->getEmail()]);

    }
}