<?php

namespace App\User\Application\UseCase\GetUser;

use App\User\Application\Dto\AuthUserDto;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUserCommand
{
    public AuthUserDto $authUserDto;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $userId;

    public function __construct(AuthUserDto $authUserDto, $userId)
    {
        $this->authUserDto = $authUserDto;
        $this->userId = $userId;
    }
}