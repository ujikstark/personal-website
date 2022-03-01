<?php

declare(strict_types=1);

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Functional\AbstractEndPoint;

class TodoTest extends AbstractEndPoint
{
    private const TODOS_URI = '/api/todos';

    public function testGetTodos(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            self::TODOS_URI,
            '',
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPostTodo(): string
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            self::TODOS_URI,
            $this->getPayload(),
            [],
            true,
            ''
        );

        $content = $response->getContent();
        $contentDecoded = json_decode($content, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        return (string) $contentDecoded['id'];


    }

    private function getPayload(): string
    {
        $faker = Factory::create();
        $name = $faker->word();
        $description = $faker->sentence();
        $date = (new \DateTime())->getTimestamp() * 1000;

        return sprintf('{"name":"%s","description":"%s","isDone":false}', $name, $description);
    }
}