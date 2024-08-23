<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\GetBearerToken;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetBearerTokenCommand
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $login;

    #[Assert\NotBlank]
    public string $grantRole;

    public function __construct(
        string $login,
        string $grantRole
    ) {
        $this->login = $login;
        $this->grantRole = $grantRole;
    }
}
