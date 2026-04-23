<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class SerializerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const string PLUGINS_SERIALIZER_NORMALIZER = 'PLUGINS_SERIALIZER_NORMALIZER';

    public const string PLUGINS_SERIALIZER_ENCODER = 'PLUGINS_SERIALIZER_ENCODER';

    public function provideServiceDependencies(Container $container): Container
    {
        $container = parent::provideServiceDependencies($container);
        $container = $this->addSerializerNormalizerPlugins($container);
        $container = $this->addSerializerEncoderPlugins($container);

        return $container;
    }

    protected function addSerializerNormalizerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SERIALIZER_NORMALIZER, function () {
            return $this->getSerializerNormalizerPlugins();
        });

        return $container;
    }

    protected function addSerializerEncoderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SERIALIZER_ENCODER, function () {
            return $this->getSerializerEncoderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerNormalizerPluginInterface>
     */
    protected function getSerializerNormalizerPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerEncoderPluginInterface>
     */
    protected function getSerializerEncoderPlugins(): array
    {
        return [];
    }
}
