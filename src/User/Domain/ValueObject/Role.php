<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class Role
{
    private const string TEST_ADMIN = 'testAdmin';
    private const string TEST_USER = 'testUser';

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, [self::TEST_ADMIN, self::TEST_USER]);

        $this->value = $value;
    }

    public static function testAdmin(): self
    {
        return new self(self::TEST_ADMIN);
    }

    public static function testUser(): self
    {
        return new self(self::TEST_USER);
    }

    public function hasAdminPermission(): bool
    {
        return self::TEST_ADMIN === $this->value;
    }

    public function hasUserPermission(): bool
    {
        return self::TEST_USER === $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
