<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\UseCase\DeleteUser;

use App\Tests\Builder\AuthUserDtoBuilder;
use App\Tests\Builder\UserBuilder;
use App\User\Application\UseCase\DeleteUser\DeleteUserCommand;
use App\User\Application\UseCase\DeleteUser\DeleteUserCommandHandler;
use App\User\Domain\Exception\RolePermissionDeniedException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Role;
use PHPUnit\Framework\TestCase;

final class DeleteUserCommandHandlerTest extends TestCase
{
    public function testCanHandle(): void
    {
        $user = (new UserBuilder())->build();
        $userId = $user->getId();
        $authUserDto = (new AuthUserDtoBuilder())->fromUser($user)->build();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasById')
            ->with($userId)
            ->willReturn(true);
        $userRepository->expects($this->once())->method('delete')
            ->with($userId);

        $deleteUserCommand = new DeleteUserCommand($authUserDto, $userId->getValue());

        $deleteUserCommandHandler = new DeleteUserCommandHandler($userRepository);

        $deleteUserCommandHandler->handle($deleteUserCommand);
    }

    public function testCanHandleUserNotFound(): void
    {
        $user = (new UserBuilder())->build();
        $userId = $user->getId();
        $authUserDto = (new AuthUserDtoBuilder())->fromUser($user)->build();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasById')
            ->with($userId)
            ->willReturn(false);
        $userRepository->expects($this->never())->method('delete');

        $deleteUserCommand = new DeleteUserCommand($authUserDto, $userId->getValue());

        $deleteUserCommandHandler = new DeleteUserCommandHandler($userRepository);

        $this->expectException(UserNotFoundException::class);

        $deleteUserCommandHandler->handle($deleteUserCommand);
    }

    public function testCanDeleteUserWithAdminPermissions(): void
    {
        $user = (new UserBuilder())->build();
        $userId = $user->getId();
        $authUserDto = (new AuthUserDtoBuilder())->fromUser($user)
            ->build();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasById')
            ->with($userId)
            ->willReturn(true);
        $userRepository->expects($this->once())->method('delete')
            ->with($userId);

        $deleteUserCommand = new DeleteUserCommand($authUserDto, $userId->getValue());

        $deleteUserCommandHandler = new DeleteUserCommandHandler($userRepository);

        $deleteUserCommandHandler->handle($deleteUserCommand);
    }

    public function testCanHandleDeletingUserWithNonAdminPermissions(): void
    {
        $user = (new UserBuilder())->build();
        $userId = $user->getId();
        $authUserDto = (new AuthUserDtoBuilder())->fromUser($user)
            ->withRole(Role::testUser())
            ->build();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasById')
            ->with($userId)
            ->willReturn(true);
        $userRepository->expects($this->never())->method('delete');

        $deleteUserCommand = new DeleteUserCommand($authUserDto, $userId->getValue());

        $deleteUserCommandHandler = new DeleteUserCommandHandler($userRepository);

        $this->expectException(RolePermissionDeniedException::class);

        $deleteUserCommandHandler->handle($deleteUserCommand);
    }
}
