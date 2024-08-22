<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Login;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function hasByLogin(Login $login): bool;
}
