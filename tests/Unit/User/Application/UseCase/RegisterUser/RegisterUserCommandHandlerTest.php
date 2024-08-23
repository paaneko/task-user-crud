<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Application\UseCase\RegisterUser;

use App\Tests\Builder\AuthUserDtoBuilder;
use App\Tests\Builder\UserBuilder;
use App\User\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\User\Application\UseCase\RegisterUser\RegisterUserCommandHandler;
use App\User\Domain\Exception\LoginAlreadyRegisteredException;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class RegisterUserCommandHandlerTest extends TestCase
{
    public function testCanHandle(): void
    {
        $expectedUser = (new UserBuilder())->build();
        $authUserDto = (new AuthUserDtoBuilder())->fromUser($expectedUser)->build();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasByLogin')
            ->willReturn(false);
        $userRepository->expects($this->once())->method('save');
        $passwordHasher = $this->createStub(PasswordHasherInterface::class);
        $passwordHasher->method('hash')
            ->willReturn($expectedUser->getHashedPassword());
        $passwordHasherFactory = $this->createStub(PasswordHasherFactoryInterface::class);
        $passwordHasherFactory->method('getPasswordHasher')->willReturn($passwordHasher);

        $registerUserCommand = new RegisterUserCommand(
            $authUserDto,
            $expectedUser->getId()->getValue(),
            $expectedUser->getLogin()->getValue(),
            $expectedUser->getPhone()->getValue(),
            $expectedUser->getHashedPassword()
        );

        $registerUserCommandHandler = new RegisterUserCommandHandler(
            $userRepository,
            $passwordHasherFactory
        );

        $user = $registerUserCommandHandler->handle($registerUserCommand);

        $this->assertEquals($expectedUser, $user);
    }

    public function testCanHandleLoginAlreadyRegistered(): void
    {
        $existingUser = (new UserBuilder())->build();
        $authUserDto = (new AuthUserDtoBuilder())->fromUser($existingUser)->build();
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())->method('hasByLogin')
            ->willReturn(true);
        $userRepository->expects($this->never())->method('save');
        $passwordHasher = $this->createStub(PasswordHasherInterface::class);
        $passwordHasherFactory = $this->createStub(PasswordHasherFactoryInterface::class);
        $passwordHasherFactory->method('getPasswordHasher')->willReturn($passwordHasher);

        $registerUserCommand = new RegisterUserCommand(
            $authUserDto,
            $existingUser->getId()->getValue(),
            $existingUser->getLogin()->getValue(),
            $existingUser->getPhone()->getValue(),
            $existingUser->getHashedPassword()
        );

        $registerUserCommandHandler = new RegisterUserCommandHandler(
            $userRepository,
            $passwordHasherFactory
        );

        $this->expectException(LoginAlreadyRegisteredException::class);

        $registerUserCommandHandler->handle($registerUserCommand);
    }
}
