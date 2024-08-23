<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\GetUser;

use App\User\Domain\Entity\User;
use App\User\Domain\Exception\RolePermissionDeniedException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Role;

final class GetUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(GetUserCommand $command): User
    {
        $authUserRole = new Role($command->authUserDto->role);

        if (!$user = $this->userRepository->findById(new Id($command->userId))) {
            throw new UserNotFoundException();
        }

        if (!$authUserRole->hasAdminPermission() && !$user->getId()->equals($command->authUserDto->userId)) {
            throw new RolePermissionDeniedException();
        }

        return $user;
    }
}
