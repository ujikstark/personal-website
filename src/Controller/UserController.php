<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

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
}
