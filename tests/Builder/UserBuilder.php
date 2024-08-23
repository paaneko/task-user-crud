<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Phone;

final class UserBuilder
{
    private Id $userId;

    private Login $login;

    private Phone $phone;

    private string $hashedPassword;

    public function __construct()
    {
        $this->userId = new Id('1');
        $this->login = new Login('login');
        $this->phone = new Phone('12345678');
        $this->hashedPassword = 'hash';
    }

    public function withId(string $id): self
    {
        $clone = clone $this;
        $clone->userId = new Id($id);

        return $clone;
    }

    public function withLogin(string $login): self
    {
        $clone = clone $this;
        $clone->login = new Login($login);

        return $clone;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;
        $clone->hashedPassword = $passwordHash;

        return $clone;
    }

    public function withPhone(string $phone): self
    {
        $clone = clone $this;
        $clone->phone = new Phone($phone);

        return $clone;
    }

    public function build(): User
    {
        return new User(
            $this->userId,
            $this->login,
            $this->phone,
            $this->hashedPassword,
        );
    }
}
