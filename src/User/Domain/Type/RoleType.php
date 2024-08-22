<?php

declare(strict_types=1);

namespace App\User\Domain\Type;

use App\User\Domain\ValueObject\Role;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class RoleType extends GuidType
{
    public const string NAME = 'user_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Role ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Role
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new Role((string) $value) : null;
    }
}
