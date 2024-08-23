<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\RegisterUser;

use App\User\Domain\Entity\User;
use App\User\Domain\Exception\LoginAlreadyRegisteredException;
use App\User\Domain\Exception\RolePermissionDeniedException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Phone;
use App\User\Domain\ValueObject\Role;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function handle(RegisterUserCommand $command): User
    {
        $login = new Login($command->login);

        if ($this->userRepository->hasByLogin($login)) {
            throw new LoginAlreadyRegisteredException();
        }

        $userPasswordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $user = new User(
            new Id($command->userId),
            $login,
            new Phone($command->phone),
            $userPasswordHasher->hash($command->pass),
        );

        $this->userRepository->save($user);

        return $user;
    }
}
