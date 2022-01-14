<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private const SERVER_INFORMATIONS = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];

    public function getResponseFromRequest(string $method, string $uri, string $payload = ''): Response
    {
        $client = self::createClient();

        $client->request(
            $method,
            $uri.'.json',
            [],
            [],
            self::SERVER_INFORMATIONS,
            $payload
        );

        return $client->getResponse();
    }
}