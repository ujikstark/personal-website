<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Model\User\CreateUserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    public function __construct(
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $hasher,
        private UserRepository $userRepository)
    {
        $this->validator = Validation::createValidator();
    }

    #[Route('/users', name: 'user', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = [];
        for ($i = 0; $i < count($users); $i++) {
            $data[] = [
                'id' => $users[$i]->getId(),
                'email' => $users[$i]->getEmail()
            ];
        }

        return $this->json([

            'data' => $data
        ]);
    }

    #[Route('/users', name: 'users_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, CreateUserDTO $data): JsonResponse
    {   
        
        $request = json_decode($request, true);
        $data->setEmail($request['email']);
        $data->setName($request['name']);
        $data->setPassword($request['password']);

        if (count($errors = $this->validator->validate($data)) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        
        }
    

    
        $user = $this->userRepository->findOneBy([
            'email' => $data->getEmail()
        ]);

        if (null !== $user) {
            return $this->json(['message' => 'This email is already exists', Response::HTTP_BAD_REQUEST]);
        }

        $newUser = new User();

        $newUser->setEmail($data->getEmail());
        $newUser->setName($data->getName());
        $newUser->setPassword($this->hasher->hashPassword($user, $data->getPassword()));
        
        $this->userRepository->save($newUser);

        return $this->json([
            'id' => $newUser->getId, 
            'createdAt' => $newUser->getCreatedAt(),
            'name' => $newUser->getName()
        ], Response::HTTP_CREATED);
        
    
    }
}
