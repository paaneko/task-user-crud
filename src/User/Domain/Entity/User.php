<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\User\Domain\Exception\SimilarPasswordException;
use App\User\Domain\Type\IdType;
use App\User\Domain\Type\LoginType;
use App\User\Domain\Type\PhoneType;
use App\User\Domain\ValueObject\Id;
use App\User\Domain\ValueObject\Login;
use App\User\Domain\ValueObject\Phone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity]
#[ORM\Table]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: IdType::NAME, length: 8)]
    #[Groups(['put', 'post'])]
    private Id $id;

    #[ORM\Column(type: LoginType::NAME, length: 8)]
    #[Groups(['post', 'get'])]
    private Login $login;

    #[ORM\Column(type: PhoneType::NAME, length: 8)]
    #[Groups(['post', 'get'])]
    private Phone $phone;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['post', 'get'])]
    #[SerializedName('pass')]
    private string $hashedPassword;

    public function __construct(
        Id $id,
        Login $login,
        Phone $phone,
        string $hashedPassword
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->phone = $phone;
        $this->hashedPassword = $hashedPassword;
    }

    public function update(Phone $newPhone, string $newHashedPassword): void
    {
        $this->phone = $newPhone;

        if ($this->hashedPassword === $newHashedPassword) {
            throw new SimilarPasswordException();
        }

        $this->hashedPassword = $newHashedPassword;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }
}
