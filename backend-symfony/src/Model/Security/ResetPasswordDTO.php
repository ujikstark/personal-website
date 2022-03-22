<?php

declare(strict_types=1);

namespace Model\Security;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    collectionOperations: [
        'resetPassword' => [
            'path' => '/security/reset-password',
            'input' => ResetPasswordDTO::class,
            'output' => false,
            'method' => Request::METHOD_POST,
            'openapi_context' => [
                'tags' => ['Security'],
                'summary' => 'Reset the user\'s password.',
                'description' => 'Reset the user\'s password.',
                'security' => [],
                'responses' => [
                    Response::HTTP_OK => [
                        'description' => 'Password successfully updated.',
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
                    Response::HTTP_FORBIDDEN => [
                        'description' => 'Invalid reset token.',
                        'content' => [
                            'application/json' => [],
                        ],
                    ],
                ],
            ]
        ]
    ],
    itemOperations: [],
    formats: ['json']
)]
class ResetPasswordDTO
{
    public string $token = '';

    #[
        Assert\Length(min: 4),
        Assert\Regex(pattern: '/\d/')
    ]
    public string $password = '';

    public string $confirmPassword = '';
}