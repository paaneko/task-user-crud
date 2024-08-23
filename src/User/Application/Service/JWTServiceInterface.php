<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Dto\AuthUserDto;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Role;

interface JWTServiceInterface
{
    public function encode(Id $id, Login $login, Role $role): string;

    public function decode(string $jwt): AuthUserDto;
}
