<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer;

use Generated\Shared\Transfer\SerializerContextTransfer;

interface SerializerInterface
{
    public function serialize(mixed $data, string $format, ?SerializerContextTransfer $serializerContextTransfer = null): string;

    /**
     * @template T of object
     *
     * @param mixed $data
     * @param class-string<T> $type
     * @param string $format
     * @param \Generated\Shared\Transfer\SerializerContextTransfer|null $serializerContextTransfer
     *
     * @return T
     */
    public function deserialize(mixed $data, string $type, string $format, ?SerializerContextTransfer $serializerContextTransfer = null): mixed;

    /**
     * Normalizes data into a set of arrays/scalars.
     *
     * @return \ArrayObject|array|string|float|int|bool|null
     */
    public function normalize(
        mixed $data,
        ?string $format = null,
        ?SerializerContextTransfer $serializerContextTransfer = null
    ): array|string|int|float|bool|\ArrayObject|null;

    /**
     * @template T of object
     *
     * @param mixed $data
     * @param class-string<T> $type
     * @param string|null $format
     * @param \Generated\Shared\Transfer\SerializerContextTransfer|null $serializerContextTransfer
     * @param object|null $objectToPopulate
     *
     * @return T
     */
    public function denormalize(
        mixed $data,
        string $type,
        ?string $format = null,
        ?SerializerContextTransfer $serializerContextTransfer = null,
        ?object $objectToPopulate = null,
    ): mixed;
}
