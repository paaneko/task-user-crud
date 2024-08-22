<?php

namespace App\Tests\Unit\User\Application\UseCase\DeleteUser;

use App\Tests\Builder\UserBuilder;
use App\User\Application\UseCase\DeleteUser\DeleteUserCommand;
use App\User\Application\UseCase\DeleteUser\DeleteUserCommandHandler;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DeleteUserCommandHandlerTest extends TestCase
{
    public function testCanHandle(): void
    {
        $userId = (new UserBuilder())->build()->getId();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasById')
            ->with($userId)
            ->willReturn(true);
        $userRepository->expects($this->once())->method('delete')
            ->with($userId);

        $deleteUserCommand = new DeleteUserCommand($userId->getValue());

        $deleteUserCommandHandler = new DeleteUserCommandHandler($userRepository);

        $deleteUserCommandHandler->handle($deleteUserCommand);
    }

    public function testCanHandleUserNotFound(): void
    {
        $userId = (new UserBuilder())->build()->getId();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasById')
            ->with($userId)
            ->willReturn(false);
        $userRepository->expects($this->never())->method('delete');

        $deleteUserCommand = new DeleteUserCommand($userId->getValue());

        $deleteUserCommandHandler = new DeleteUserCommandHandler($userRepository);

        $this->expectException(UserNotFoundException::class);

        $deleteUserCommandHandler->handle($deleteUserCommand);
    }
}
