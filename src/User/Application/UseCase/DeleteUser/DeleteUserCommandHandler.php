<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\DeleteUser;

use App\User\Domain\Exception\RolePermissionDeniedException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Role;

final class DeleteUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(DeleteUserCommand $command): void
    {
        $authUserRole = new Role($command->authUserDto->role);
        $userId = new Id($command->userId);

        if (!$this->userRepository->hasById($userId)) {
            throw new UserNotFoundException();
        }

        if (!$authUserRole->hasAdminPermission()) {
            throw new RolePermissionDeniedException();
        }

        $this->userRepository->delete($userId);
    }
}
