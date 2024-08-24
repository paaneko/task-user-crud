<?php

declare(strict_types=1);

namespace App\User\Application\Serializer\Normalizer;

use App\User\Domain\ValueObject\Phone;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PhoneNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): string
    {
        /* @var Phone $object */
        return $object->getValue();
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Phone;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Phone::class => true];
    }
}
