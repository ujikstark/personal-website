<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\Account;

use App\DataFixtures\UserFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\AbstractEndPoint;

class UpdatePasswordControllerTest extends AbstractEndPoint 
{
    private const UPDATE_PASSWORD_URI = '/api/account/update-password';

    public function testUpdatePassword(): void
    {
        $payload = sprintf(
            '{"currentPassword": "%s","newPassword": "%s","confirmPassword": "%s"}',
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::UPDATE_PASSWORD_URI,
            $payload,
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);
        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertArrayHasKey('message', $contentDecoded);
    }

    public function testUpdatePasswordNotLoggedIn(): void
    {
        $payload = sprintf(
            '{"currentPassword": "%s","newPassword": "%s","confirmPassword": "%s"}',
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::UPDATE_PASSWORD_URI,
            $payload,
            [],
            false,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);
        
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertArrayHasKey('message', $contentDecoded);
    }

    public function testUpdatePasswordWithWrongPassword(): void
    {
        $payload = sprintf(
            '{"currentPassword": "%s","newPassword": "%s","confirmPassword": "%s"}',
            'password',
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD,
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::UPDATE_PASSWORD_URI,
            $payload,
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertArrayHasKey('detail', $contentDecoded);
        $this->assertArrayHasKey('violations', $contentDecoded);
        $this->assertEquals('currentPassword', $contentDecoded['violations'][0]['propertyPath']);
    }

    public function testUpdatePasswordWithDifferentPassword(): void
    {
        $payload = sprintf(
            '{"currentPassword": "%s","newPassword": "%s","confirmPassword": "%s"}',
            UserFixtures::DEFAULT_PASSWORD,
            UserFixtures::DEFAULT_PASSWORD,
            'password'
        );

        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::UPDATE_PASSWORD_URI,
            $payload,
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertArrayHasKey('detail', $contentDecoded);
        $this->assertArrayHasKey('violations', $contentDecoded);
        $this->assertEquals('confirmPassword', $contentDecoded['violations'][0]['propertyPath']);
    }
}