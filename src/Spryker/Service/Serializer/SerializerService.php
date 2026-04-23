<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer;

use Generated\Shared\Transfer\SerializerContextTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Serializer\SerializerServiceFactory getFactory()
 */
class SerializerService extends AbstractService implements SerializerServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function serialize(mixed $data, string $format, ?SerializerContextTransfer $serializerContextTransfer = null): string
    {
        return $this->getFactory()
            ->createSerializer()
            ->serialize($data, $format, $serializerContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @template T of object
     *
     * @param class-string<T> $type
     *
     * @return T
     */
    public function deserialize(mixed $data, string $type, string $format, ?SerializerContextTransfer $serializerContextTransfer = null): mixed
    {
        return $this->getFactory()
            ->createSerializer()
            ->deserialize($data, $type, $format, $serializerContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \ArrayObject|array|string|float|int|bool|null
     */
    public function normalize(
        mixed $data,
        ?string $format = null,
        ?SerializerContextTransfer $serializerContextTransfer = null
    ): array|string|int|float|bool|\ArrayObject|null {
        return $this->getFactory()
            ->createSerializer()
            ->normalize($data, $format, $serializerContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @template T of object
     *
     * @param class-string<T> $type
     *
     * @return T
     */
    public function denormalize(
        mixed $data,
        string $type,
        ?string $format = null,
        ?SerializerContextTransfer $serializerContextTransfer = null,
        ?object $objectToPopulate = null,
    ): mixed {
        return $this->getFactory()
            ->createSerializer()
            ->denormalize($data, $type, $format, $serializerContextTransfer, $objectToPopulate);
    }
}
