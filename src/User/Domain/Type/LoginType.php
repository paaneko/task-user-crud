<?php

declare(strict_types=1);

namespace App\User\Domain\Type;

use App\User\Domain\ValueObject\Login;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class LoginType extends GuidType
{
    public const string NAME = 'user_login';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Login ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Login
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new Login((string) $value) : null;
    }
}
