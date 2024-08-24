<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\PutUser;

use App\User\Domain\Entity\User;
use App\User\Domain\Exception\RolePermissionDeniedException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Phone;
use App\User\Domain\ValueObject\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class PutUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EntityManagerInterface $entityManager,
        private PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function handle(PutUserCommand $command): User
    {
        $authUserLogin = new Login($command->authUserDto->login);
        $authUserRole = new Role($command->authUserDto->role);

        if (!$user = $this->userRepository->findByLogin(new Login($command->login))) {
            throw new UserNotFoundException();
        }

        if (!$authUserRole->hasAdminPermission() && !$user->getLogin()->equals($authUserLogin->getValue())) {
            throw new RolePermissionDeniedException();
        }

        $userPasswordHasher = $this->passwordHasherFactory->getPasswordHasher(User::class);
        $user->update(
            new Phone($command->phone),
            $userPasswordHasher->hash($command->pass)
        );

        $this->entityManager->flush();

        return $user;
    }
}
