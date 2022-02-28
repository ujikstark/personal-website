<?php

declare(strict_types=1);

namespace Test\Functional\Controller;

use App\DataFixtures\UserFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\AbstractEndPoint;

class GetMeControllerTest extends AbstractEndPoint
{
    private const GET_ME_URI = '/api/account/me';

    public function testGetMe()
    {

        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            self::GET_ME_URI,
            '',
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(UserFixtures::DEFAULT_EMAIL, $contentDecoded['email']);
        $this->assertEquals(UserFixtures::DEFAULT_NAME, $contentDecoded['name']);
        $this->assertCount(4, $contentDecoded);
    }

    public function testGetMeNotLoggedIn(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            self::GET_ME_URI,
            '',
            [],
            false,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $contentDecoded['code']);
    }
}
