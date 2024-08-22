<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\DeleteUser;

use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Id;

final class DeleteUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(DeleteUserCommand $command): void
    {
        $userId = new Id($command->userId);

        if (!$this->userRepository->hasById($userId)) {
            throw new UserNotFoundException();
        }

        $this->userRepository->delete($userId);
    }
}
