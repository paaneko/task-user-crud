<?php

namespace App\User\Application\UseCase\PutUser;

use App\User\Application\Dto\AuthUserDto;
use Symfony\Component\Validator\Constraints as Assert;

class PutUserCommand
{
    public AuthUserDto $authUserDto;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $login;

    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $pass;

    public function __construct(AuthUserDto $authUserDto, string $phone, string $login, string $pass)
    {
        $this->authUserDto = $authUserDto;
        $this->phone = $phone;
        $this->login = $login;
        $this->pass = $pass;
    }
}