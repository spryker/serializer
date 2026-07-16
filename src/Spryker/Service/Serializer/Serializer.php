<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer;

use Generated\Shared\Transfer\SerializerContextTransfer;
use Spryker\Service\Serializer\Builder\SerializerBuilder;
use Spryker\Service\Serializer\Mapper\SerializerContextMapperInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer implements SerializerInterface
{
    protected ?SymfonySerializer $symfonySerializer = null;

    /**
     * @param \Spryker\Service\Serializer\Builder\SerializerBuilder $serializerBuilder
     * @param \Spryker\Service\Serializer\Mapper\SerializerContextMapperInterface $serializerContextMapper
     * @param array<\Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerNormalizerPluginInterface> $serializerNormalizerPlugins
     * @param array<\Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerEncoderPluginInterface> $serializerEncoderPlugins
     */
    public function __construct(
        protected SerializerBuilder $serializerBuilder,
        protected SerializerContextMapperInterface $serializerContextMapper,
        protected array $serializerNormalizerPlugins,
        protected array $serializerEncoderPlugins,
    ) {
    }

    public function serialize(mixed $data, string $format, ?SerializerContextTransfer $serializerContextTransfer = null): string
    {
        $context = $this->serializerContextMapper->mapSerializerContextTransferToContext($serializerContextTransfer);

        return $this->getSymfonySerializer()->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, ?SerializerContextTransfer $serializerContextTransfer = null): mixed
    {
        $context = $this->serializerContextMapper->mapSerializerContextTransferToContext($serializerContextTransfer);

        return $this->getSymfonySerializer()->deserialize($data, $type, $format, $context);
    }

    public function normalize(
        mixed $data,
        ?string $format = null,
        ?SerializerContextTransfer $serializerContextTransfer = null
    ): array|string|int|float|bool|\ArrayObject|null {
        $context = $this->serializerContextMapper->mapSerializerContextTransferToContext($serializerContextTransfer);

        return $this->getSymfonySerializer()->normalize($data, $format, $context);
    }

    public function denormalize(
        mixed $data,
        string $type,
        ?string $format = null,
        ?SerializerContextTransfer $serializerContextTransfer = null,
        ?object $objectToPopulate = null,
    ): mixed {
        $context = $this->serializerContextMapper->mapSerializerContextTransferToContext($serializerContextTransfer);

        if ($objectToPopulate !== null) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $objectToPopulate;
        }

        return $this->getSymfonySerializer()->denormalize($this->sanitizeDenormalizationInput($data), $type, $format, $context);
    }

    /**
     * Denormalization input is frequently a trusted transfer array (`$transfer->toArray()`) that
     * still carries value objects such as `Spryker\DecimalObject\Decimal` in numeric fields.
     * Symfony cannot assign such a value object to a scalar (`int`/`float`) property, so reduce any
     * decimal value object to its numeric scalar before denormalization. It is detected structurally
     * (rather than by class) to avoid coupling this generic service to the decimal-object module.
     * Scalars, nested arrays and other objects are left untouched.
     */
    protected function sanitizeDenormalizationInput(mixed $data): mixed
    {
        if (is_object($data) && method_exists($data, 'isInteger') && method_exists($data, 'toInt') && method_exists($data, 'toFloat')) {
            return $data->isInteger() ? $data->toInt() : $data->toFloat();
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeDenormalizationInput($value);
            }
        }

        return $data;
    }

    protected function getSymfonySerializer(): SymfonySerializer
    {
        if ($this->symfonySerializer !== null) {
            return $this->symfonySerializer;
        }

        $this->symfonySerializer = $this->serializerBuilder->build(
            $this->collectNormalizers(),
            $this->collectEncoders(),
        );

        return $this->symfonySerializer;
    }

    /**
     * @return array<\Symfony\Component\Serializer\Normalizer\NormalizerInterface|\Symfony\Component\Serializer\Normalizer\DenormalizerInterface>
     */
    protected function collectNormalizers(): array
    {
        $normalizers = [];

        foreach ($this->serializerNormalizerPlugins as $normalizerPlugin) {
            $normalizers = array_merge($normalizers, $normalizerPlugin->getNormalizers());
        }

        return $normalizers;
    }

    /**
     * @return array<\Symfony\Component\Serializer\Encoder\EncoderInterface|\Symfony\Component\Serializer\Encoder\DecoderInterface>
     */
    protected function collectEncoders(): array
    {
        $encoders = [];

        foreach ($this->serializerEncoderPlugins as $encoderPlugin) {
            $encoders = array_merge($encoders, $encoderPlugin->getEncoders());
        }

        return $encoders;
    }
}
