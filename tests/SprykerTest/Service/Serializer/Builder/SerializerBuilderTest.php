<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Serializer\Builder;

use Codeception\Test\Unit;
use Spryker\Service\Serializer\Builder\SerializerBuilder;
use stdClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Serializer
 * @group Builder
 * @group SerializerBuilderTest
 * Add your own group annotations below this line
 */
class SerializerBuilderTest extends Unit
{
    public function testGivenDefaultConfigWhenBuildThenReturnsSymfonySerializerInstance(): void
    {
        // Arrange
        $builder = new SerializerBuilder();

        // Act
        $serializer = $builder->build();

        // Assert
        $this->assertInstanceOf(SymfonySerializer::class, $serializer);
    }

    public function testGivenDefaultBuildWhenSerializeToJsonThenReturnsJsonString(): void
    {
        // Arrange
        $builder = new SerializerBuilder();
        $serializer = $builder->build();

        // Act
        $result = $serializer->serialize(['key' => 'value'], 'json');

        // Assert
        $this->assertSame('{"key":"value"}', $result);
    }

    public function testGivenCustomNormalizerWhenBuildThenPrependsItBeforeBuiltIns(): void
    {
        // Arrange
        $builder = new SerializerBuilder();
        $customNormalizer = $this->createMock(NormalizerInterface::class);
        $customNormalizer->method('supportsNormalization')
            ->willReturn(true);
        $customNormalizer->method('normalize')
            ->willReturn(['custom' => true]);

        // Act
        $serializer = $builder->build([$customNormalizer]);
        $result = $serializer->normalize(new stdClass());

        // Assert
        $this->assertSame(['custom' => true], $result);
    }
}
