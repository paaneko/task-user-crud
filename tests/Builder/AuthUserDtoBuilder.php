<?php

namespace App\Tests\Builder;

use App\User\Application\Dto\AuthUserDto;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Role;

class AuthUserDtoBuilder
{
    private string $userId;

    private string $login;

    private string $role;

    public function __construct()
    {
        $this->role = Role::testAdmin()->getValue();
    }

    public function fromUser(User $user): self
    {
        $clone = clone $this;
        $clone->userId = $user->getId()->getValue();
        $clone->login = $user->getLogin()->getValue();
        return $clone;
    }

    public function withRole(Role $role): self
    {
        $clone = clone $this;
        $clone->role = $role->getValue();
        return $clone;
    }

    public function build(): AuthUserDto
    {
        return new AuthUserDto(
            $this->userId,
            $this->login,
            $this->role
        );
    }
}