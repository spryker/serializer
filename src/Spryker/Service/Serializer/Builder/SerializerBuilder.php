<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer\Builder;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class SerializerBuilder
{
    /**
     * @param array<\Symfony\Component\Serializer\Normalizer\NormalizerInterface|\Symfony\Component\Serializer\Normalizer\DenormalizerInterface> $additionalNormalizers
     * @param array<\Symfony\Component\Serializer\Encoder\EncoderInterface|\Symfony\Component\Serializer\Encoder\DecoderInterface> $additionalEncoders
     *
     * @return \Symfony\Component\Serializer\Serializer
     */
    public function build(array $additionalNormalizers = [], array $additionalEncoders = []): SymfonySerializer
    {
        $normalizers = array_merge($additionalNormalizers, $this->getBuiltInNormalizers());
        $encoders = array_merge($additionalEncoders, $this->getBuiltInEncoders());

        return new SymfonySerializer($normalizers, $encoders);
    }

    /**
     * @return array<\Symfony\Component\Serializer\Normalizer\NormalizerInterface|\Symfony\Component\Serializer\Normalizer\DenormalizerInterface>
     */
    protected function getBuiltInNormalizers(): array
    {
        return [
            new UnwrappingDenormalizer(),
            new UidNormalizer(),
            new DateTimeNormalizer(),
            new DateTimeZoneNormalizer(),
            new DateIntervalNormalizer(),
            new BackedEnumNormalizer(),
            new DataUriNormalizer(),
            new JsonSerializableNormalizer(),
            new ArrayDenormalizer(),
            // The ReflectionExtractor lets the ObjectNormalizer denormalize nested typed value
            // objects (e.g. an Address inside a shipment). It also turns on strict scalar type
            // enforcement, which rejects benign mismatches when mapping trusted internal transfer
            // arrays onto generated resources (e.g. an int taxRate for a float property, since
            // int->float coercion only applies to JSON-format input). DISABLE_TYPE_ENFORCEMENT
            // restores the pre-extractor leniency for scalars (PHP coerces on assignment) while
            // keeping nested-object denormalization, which runs before the enforcement check.
            new ObjectNormalizer(
                propertyTypeExtractor: new ReflectionExtractor(),
                defaultContext: [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true],
            ),
        ];
    }

    /**
     * @return array<\Symfony\Component\Serializer\Encoder\EncoderInterface|\Symfony\Component\Serializer\Encoder\DecoderInterface>
     */
    protected function getBuiltInEncoders(): array
    {
        return [
            new JsonEncoder(),
            new XmlEncoder(),
            new CsvEncoder(),
        ];
    }
}
