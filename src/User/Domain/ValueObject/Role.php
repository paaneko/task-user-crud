<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class Role
{
    private const string ADMIN = 'admin';
    private const string USER = 'user';

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, [self::USER, self::ADMIN]);

        $this->value = $value;
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
