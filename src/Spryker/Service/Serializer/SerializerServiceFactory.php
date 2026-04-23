<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\Serializer\Builder\SerializerBuilder;
use Spryker\Service\Serializer\Mapper\SerializerContextMapper;
use Spryker\Service\Serializer\Mapper\SerializerContextMapperInterface;

class SerializerServiceFactory extends AbstractServiceFactory
{
    public function createSerializer(): SerializerInterface
    {
        return new Serializer(
            $this->createSerializerBuilder(),
            $this->createSerializerContextMapper(),
            $this->getSerializerNormalizerPlugins(),
            $this->getSerializerEncoderPlugins(),
        );
    }

    public function createSerializerBuilder(): SerializerBuilder
    {
        return new SerializerBuilder();
    }

    public function createSerializerContextMapper(): SerializerContextMapperInterface
    {
        return new SerializerContextMapper();
    }

    /**
     * @return array<\Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerNormalizerPluginInterface>
     */
    public function getSerializerNormalizerPlugins(): array
    {
        return $this->getProvidedDependency(SerializerDependencyProvider::PLUGINS_SERIALIZER_NORMALIZER);
    }

    /**
     * @return array<\Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerEncoderPluginInterface>
     */
    public function getSerializerEncoderPlugins(): array
    {
        return $this->getProvidedDependency(SerializerDependencyProvider::PLUGINS_SERIALIZER_ENCODER);
    }
}
