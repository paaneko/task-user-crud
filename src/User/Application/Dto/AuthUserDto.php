<?php

declare(strict_types=1);

namespace App\User\Application\Dto;

final readonly class AuthUserDto
{
    public function __construct(
        public string $userId,
        public string $login,
        public string $role
    ) {
    }
}
