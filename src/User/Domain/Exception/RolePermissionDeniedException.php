<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

final class RolePermissionDeniedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Permission denied: Your role do not have enough permissions');
    }
}
