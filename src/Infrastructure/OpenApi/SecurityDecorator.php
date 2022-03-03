<?php

declare(strict_types=1);

namespace Infrastructure\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use Symfony\Component\HttpFoundation\Response;

final class SecurityDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $openApi->getPaths()->addPath('/security/login', $this->createLoginPath($schemas));
        $openApi->getPaths()->addPath('/security/refresh-token', $this->createRefreshTokenPath());
        
        return $openApi;
    }

    private function createLoginPath(?\ArrayObject $schemas): PathItem
    {
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'refresh_token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ]
            ],
        ]);

        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'johndoe@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'apassword',
                ],
            ],
        ]);

        return new PathItem(
            ref: 'JWT Token',
            post: new Operation(
                operationId: 'login',
                tags: ['Security'],
                responses: [
                    Response::HTTP_OK => [
                        'description' => 'Authentication successful',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                    Response::HTTP_UNAUTHORIZED => [
                        'description' => 'Authentication failed',
                        'content' => [
                            'application/json' => [],
                        ],
                    ],
                ],
                summary: 'Get JWT token to login.',
                requestBody: new RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
                security: [],
            ),
        );
    }

    private function createRefreshTokenPath(): PathItem
    {
        return new PathItem(
            ref: 'Refresh Token',
            post: new Operation(
                operationId: 'refreshToken',
                tags: ['Security'],
                responses: [
                    Response::HTTP_NO_CONTENT => [
                        'description' => 'Token successfully refreshed',
                        'content' => [
                            'application/json' => [],
                        ],
                    ],
                    Response::HTTP_UNAUTHORIZED => [
                        'description' => 'Token could not be refreshed',
                        'content' => [
                            'application/json' => [],
                        ],
                    ],
                ],
                summary: 'Get new JWT from refresh token.',
                requestBody: new RequestBody(
                    description: 'Refresh JW Token',
                    content: new \ArrayObject([
                        'application/json' => [],
                    ]),
                ),
                security: [],
            ),
        );
    }    
}