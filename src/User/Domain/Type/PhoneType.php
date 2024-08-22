<?php

declare(strict_types=1);

namespace App\User\Domain\Type;

use App\User\Domain\ValueObject\Phone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class PhoneType extends GuidType
{
    public const string NAME = 'user_phone';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Phone ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Phone
    {
        /* @phpstan-ignore-next-line */
        return !empty($value) ? new Phone((string) $value) : null;
    }
}
