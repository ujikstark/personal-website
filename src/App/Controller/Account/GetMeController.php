<?php

declare(strict_types=1);

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetMeController extends AbstractController 
{
    public const PATH = '/account/me';

    public function __invoke(): User
    {   
        /** @var User $user */
        $user = $this->getUser();
        
        return $user;
    }
}