<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer\Builder;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
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
            new ObjectNormalizer(),
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
