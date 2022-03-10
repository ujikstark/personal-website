<?php

declare(strict_types=1);

namespace App\Security\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceAccessException extends HttpException
{
    public const RESOURCE_ACCESS_EXCEPTION = 'This is not your resource';
}
