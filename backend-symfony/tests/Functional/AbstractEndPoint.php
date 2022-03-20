<?php

namespace Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private $token = null;

    private $serverInfo = [
        'ACCEPT' => 'application/json', 
        'CONTENT_TYPE' => 'application/json',
        'HTTP_ACCEPT' => 'application/json',
    ];

    protected const TOKEN_NOT_FOUND = 'JWT Token not found';
    protected const NOT_YOUR_RESOURCE = 'This is not your resource';
    protected const LOGIN_PAYLOAD = '{"email":"%s","password":"%s"}';

    public function getResponseFromRequest(
        string $method, 
        string $uri, 
        string $payload = '',
        array $parameters = [],
        bool $withAuthentication = true,
        string $json = '.json'
        ): Response
    {

        $client = $this->createApiClient($withAuthentication);

        $client->request(
            $method,
            $uri.$json,
            $parameters,
            [],
            $this->serverInfo,
            $payload
        );

        return $client->getResponse();
    }

    protected function createApiClient(bool $withAuthentication): KernelBrowser
    {
        $client = self::createClient();

        if (!$withAuthentication) {
            return $client;
        }

        $client->request(
            Request::METHOD_POST,
            '/security/login',
            [],
            [],
            $this->serverInfo,
            sprintf(self::LOGIN_PAYLOAD, UserFixtures::DEFAULT_EMAIL, UserFixtures::DEFAULT_PASSWORD)
        );
        
        $content = $client->getResponse()->getContent();
        
        return $client;        
    }
}