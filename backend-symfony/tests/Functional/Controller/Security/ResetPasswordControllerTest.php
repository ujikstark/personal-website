<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\Security;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Tests\Functional\AbstractEndPoint;

class ResetPasswordControllerTest extends AbstractEndPoint
{
    private const RESET_PASSWORD_URI = '/api/security/reset-password';

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
        self::ensureKernelShutdown();
    }

    public function testResetPassword(): void
    {
        $token = Uuid::v4()->toRfc4122();

        $this->setResetPasswordData($token, new \DateTimeImmutable('+1 hour'));

        $payload = sprintf(
            '{"token":"%s","password":"%s","confirmPassword":"%s"}',
            $token,
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::RESET_PASSWORD_URI,
            $payload,
            [],
            false,
            ''
        );


        $user = $this->userRepository->findOneBy([
            'email' => UserFixtures::DEFAULT_EMAIL
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertNull($user->getResetPasswordExpirationDate());
        $this->assertNull($user->getResetPasswordToken());

    }

    public function testResetPasswordWithWrongToken(): void
    {
        $payload = sprintf(
            '{"token":"%s","password":"%s","confirmPassword":"%s"}',
            'wrong token',
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::RESET_PASSWORD_URI,
            $payload,
            [],
            false,
            ''
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testResetPasswordWithExpiredToken(): void
    {
        $token = Uuid::v4()->toRfc4122();
        $this->setResetPasswordData($token, new \DateTimeImmutable('-1 hour'));

        $payload = sprintf(
            '{"token":"%s","password":"%s","confirmPassword":"%s"}',
            $token,
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::RESET_PASSWORD_URI,
            $payload,
            [],
            false,
            ''
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    private function setResetPasswordData(string $token, \DateTimeImmutable $expirationDate): void
    {
        $user = $this->userRepository->findOneBy([
            'email' => UserFixtures::DEFAULT_EMAIL,
        ]);

        $user->setResetPasswordToken($token);
        $user->setResetPasswordExpirationDate($expirationDate);

        $this->userRepository->save($user);
        $this->userRepository->clear();

    }
}