<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Spryker\Service\Serializer\Builder\SerializerBuilder;
use Spryker\Service\Serializer\Mapper\SerializerContextMapper;
use Spryker\Service\Serializer\Mapper\SerializerContextMapperInterface;
use Spryker\Service\Serializer\Serializer;
use Spryker\Service\Serializer\SerializerInterface;
use Spryker\Service\Serializer\SerializerService;
use Spryker\Service\Serializer\SerializerServiceInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(SerializerBuilder::class);
    $services->set(SerializerContextMapperInterface::class, SerializerContextMapper::class);
    $services->set(SerializerInterface::class, Serializer::class);
    $services->set(SerializerServiceInterface::class, SerializerService::class);
};
