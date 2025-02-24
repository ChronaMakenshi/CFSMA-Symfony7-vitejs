<?php

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MyCustomProblemNormalizer implements NormalizerInterface
{
    /**
     * Normalizes an exception into a structured array.
     *
     * @param FlattenException $exception
     * @param string|null $format
     * @param array $context
     *
     * @return array
     */
    public function normalize($exception, string $format = null, array $context = []): array
    {
        if (!$exception instanceof FlattenException) {
            throw new \InvalidArgumentException('Expected exception of type FlattenException.');
        }

        return [
            'content' => 'This is my custom problem normalizer.',
            'exception' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getStatusCode(),
            ],
        ];
    }

    /**
     * Checks whether the data can be normalized by this normalizer.
     *
     * @param mixed $data
     * @param string|null $format
     * @param array $context
     *
     * @return bool
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FlattenException;
    }

    /**
     * Returns the types supported by the normalizer.
     *
     * @param string|null $format
     *
     * @return array
     */
    public function getSupportedTypes(?string $format): array
    {
        return [FlattenException::class => true];
    }
}
