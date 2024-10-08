<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\RegisterUser;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RegisterUserCommand
{
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
        string $userId,
        string $login,
        string $phone,
        string $pass
    ) {
        $this->userId = $userId;
        $this->login = $login;
        $this->phone = $phone;
        $this->pass = $pass;
    }
}
