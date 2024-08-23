<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\DeleteUser;

use App\User\Application\Dto\AuthUserDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteUserCommand
{
    public AuthUserDto $authUserDto;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $userId;

    public function __construct(
        AuthUserDto $authUserDto,
        string $userId
    ) {
        $this->authUserDto = $authUserDto;
        $this->userId = $userId;
    }
}
