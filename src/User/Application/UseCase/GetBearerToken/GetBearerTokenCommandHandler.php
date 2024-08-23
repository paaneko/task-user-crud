<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\GetBearerToken;

use App\User\Application\Service\JWTServiceInterface;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Role;

final class GetBearerTokenCommandHandler
{
    public function __construct(
        private JWTServiceInterface $JWTService,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(GetBearerTokenCommand $command): string
    {
        $login = new Login($command->login);

        if (!$user = $this->userRepository->findByLogin($login)) {
            throw new UserNotFoundException();
        }

        return $this->JWTService->encode(
            $user->getId(),
            $user->getLogin(),
            new Role($command->grantRole)
        );
    }
}
