<?php

declare(strict_types=1);

namespace App\User\Application\Serializer\Normalizer;

use App\User\Domain\ValueObject\Id;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class IdNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): string
    {
        /* @var Id $object */
        return $object->getValue();
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Id;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Id::class => true];
    }
}
