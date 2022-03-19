<?php

declare(strict_types=1);


namespace Model\Account;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Account\UpdatePasswordController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'updatePassword' => [
            'path' => UpdatePasswordController::PATH,
            'controller' => UpdatePasswordController::class,
            'input' => UpdatePasswordDTO::class,
            'output' => false,
            'method' => Request::METHOD_POST,
            'openapi_context' => [
                'tags' => ['Account'],
                'summary' => 'A user can update his password.',
                'description' => 'A user can update his password.',
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
                ]
            ]
        ]
    ],
    itemOperations: [],
    formats: ['json']
)]
class UpdatePasswordDTO
{
    #[SecurityAssert\UserPassword]
    private string $currentPassword;

    #[Assert\Length(min: 4)]
    private string $newPassword;

    #[Assert\EqualTo(propertyPath: 'newPassword')]
    private string $confirmPassword;

    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(string $currentPassword): UpdatePasswordDTO
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): UpdatePasswordDTO
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): UpdatePasswordDTO
    {
        $this->confirmPassword = $confirmPassword;
        
        return $this;
    }
}