<?php

namespace Tests\Functional\Entity;

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


}
