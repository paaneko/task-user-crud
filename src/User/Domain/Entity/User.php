<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\User\Domain\Type\IdType;
use App\User\Domain\Type\LoginType;
use App\User\Domain\Type\PhoneType;
use App\User\Domain\Type\RoleType;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Phone;
use App\User\Domain\ValueObject\Role;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table]
final class User
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 8)]
    private Id $userId;

    #[ORM\Column(type: LoginType::NAME, length: 8)]
    private Login $login;

    #[ORM\Column(type: PhoneType::NAME, length: 8)]
    private Phone $phone;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $hashedPassword;

    #[ORM\Column(type: RoleType::NAME, length: 8)]
    private Role $role;

    public function __construct(
        Id $userId,
        Login $login,
        Phone $phone,
        string $hashedPassword,
        Role $role
    ) {
        $this->userId = $userId;
        $this->login = $login;
        $this->phone = $phone;
        $this->hashedPassword = $hashedPassword;
        $this->role = $role;
    }

    public function getId(): Id
    {
        return $this->userId;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
