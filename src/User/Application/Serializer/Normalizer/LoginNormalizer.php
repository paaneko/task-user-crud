<?php

declare(strict_types=1);

namespace App\User\Application\Serializer\Normalizer;

use App\User\Domain\ValueObject\Login;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class LoginNormalizer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): string
    {
        /* @var Login $object */
        return $object->getValue();
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Login;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Login::class => true];
    }
}
