<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

final class UnauthorizedAccessException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Unauthorized access: Denied please use bearer token');
    }
}
