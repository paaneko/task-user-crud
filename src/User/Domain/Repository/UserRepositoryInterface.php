<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(Id $id): ?User;

    public function findByLogin(Login $login): ?User;

    public function hasByLogin(Login $login): bool;

    public function hasById(Id $id): bool;

    public function delete(Id $id): void;
}
