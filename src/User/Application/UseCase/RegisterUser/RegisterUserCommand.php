<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\RegisterUser;

use App\User\Application\Dto\AuthUserDto;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegisterUserCommand
{
    public AuthUserDto $authUserDto;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $userId;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $pass;

    public function __construct(
        AuthUserDto $authUserDto,
        string $userId,
        string $login,
        string $phone,
        string $pass
    ) {
        $this->authUserDto = $authUserDto;
        $this->userId = $userId;
        $this->login = $login;
        $this->phone = $phone;
        $this->pass = $pass;
    }
}
