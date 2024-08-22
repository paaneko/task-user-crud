<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\DeleteUser;

use Symfony\Component\Validator\Constraints as Assert;

final class DeleteUserCommand
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 8)]
    public string $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}
