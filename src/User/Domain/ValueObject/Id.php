<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class Id
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::maxLength($value, 8);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(string $id): bool
    {
        return $this->value === $id;
    }
}
