<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\Security;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\AbstractEndPoint;

class SendResetPasswordEmailControllerTest extends AbstractEndPoint
{
    private const SEND_RESET_PASSWORD_EMAIL_URI = '/api/security/reset-password-email';

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        self::ensureKernelShutdown();
    }

    public function testSendResetPasswordEmail(): void
    {
        $payload = sprintf('{"email":"%s"}', UserFixtures::DEFAULT_EMAIL);
        
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::SEND_RESET_PASSWORD_EMAIL_URI,
            $payload,
            [],
            false,
            ''
        );

        $user = $this->userRepository->findOneBy([
            'email' => UserFixtures::DEFAULT_EMAIL
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNotNull($user->getResetPasswordExpirationDate());
        $this->assertNotNull($user->getResetPasswordExpirationDate());
    }

    public function testSendResetPasswordEmailWithNonexistentUser(): void
    {
        $payload = sprintf('{"email":"%s"}', 'fake@email.gouv');

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::SEND_RESET_PASSWORD_EMAIL_URI,
            $payload,
            [],
            false,
            ''
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}