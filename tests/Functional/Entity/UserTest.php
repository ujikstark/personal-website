<?php

namespace Tests\Functional\Entity;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\AbstractEndPoint;

class UserTest extends AbstractEndPoint
{
    private const USERS_URI = '/users';

    public function testGetUsers(): void
    {

        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            self::USERS_URI,
            '',
            [],
            true
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);
        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertNotEmpty($contentDecoded);
    }

    public function testPostUser(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::USERS_URI,
            $this->getPayload()
        );

        $content = $response->getContent();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertNotEmpty(json_decode($content));
    }

    private function getPayload(): string
    {
        $faker = Factory::create();
        $email = $faker->email();
        $name = $faker->name();

        return sprintf(
            '{"email": "%s" , "password": "password", "name": "%s"}',
            $email,
            $name
        );

    }


}
