<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

final class LoginAlreadyRegisteredException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Login already registered');
    }
}
