<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class Login
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::maxLength($value, 8);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(string $login): bool
    {
        return $this->value === $login;
    }
}
