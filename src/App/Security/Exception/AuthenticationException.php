<?php

declare(strict_types=1);

namespace App\Security\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthenticationException extends HttpException
{
    public const AUTHENTICATION_EXCEPTION = 'You are not authenticated';
}