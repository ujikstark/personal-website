<?php

declare(strict_types=1);

namespace Model\Security;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Security\SendResetPasswordEmailController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'resetPassword' => [
            'path' => SendResetPasswordEmailController::PATH,
            'controller' => SendResetPasswordEmailController::class,
            'input' => SendResetPasswordEmailDTO::class,
            'output' => false,
            'method' => Request::METHOD_POST,
            'openapi_context' => [
                'tags' => ['Security'],
                'summary' => 'Send a reset password email to the user.',
                'description' => 'Send a reset password email to the user.',
                'security' => [],
                'responses' => [
                    Response::HTTP_OK => [
                        'description' => 'Email successfully sent.',
                        'content' => [
                            'application/json' => [],
                        ],
                    ],
                    Response::HTTP_BAD_REQUEST => [
                        'description' => 'Input data is not valid.',
                        'content' => [
                            'application/json' => [],
                        ],
                    ],
                ]
            ]
        ]
    ],
    itemOperations: [],
    formats: ['json']
)]
class SendResetPasswordEmailDTO
{
   #[Assert\Email]
   public string $email = '';
}