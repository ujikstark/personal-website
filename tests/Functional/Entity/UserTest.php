<?php

namespace Tests\Functional\Entity;

use App\DataFixtures\UserFixtures;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\AbstractEndPoint;

class UserTest extends AbstractEndPoint
{
    private const USERS_URI = '/api/users';
    private $user;

    public function testGetUsers(): void
    {       

        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            self::USERS_URI,
            '',
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);
        
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertNotEmpty($contentDecoded);
    }

    public function testPostUser()
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::USERS_URI,
            $this->getPayload(),
            [],
            false,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertNotEmpty(json_decode($content));

        return (string) $contentDecoded['id'];
        
    }

    /**
     * @depends testPostUser
     */
    public function testGetDefaultUser(string $id)
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            self::USERS_URI.'/'.$id,
            '',
            [],
            true
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
        $this->assertNotEmpty($contentDecoded);

        return (string) $contentDecoded['id'];
    }


    public function testDeleteDefaultUser(): void
    {

        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            self::USERS_URI.'/'.UserFixtures::DEFAULT_UUID,
            $this->getPayload(),
            [],
            true,
            ''
        );

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    private function getPayload(): string
    {
        $faker = Factory::create();
        $email = $faker->email();
        $name = $faker->name();

        return sprintf(
            '{"email": "%s" , "password": "password1", "name": "%s"}',
            $email,
            $name
        );

    }


}
