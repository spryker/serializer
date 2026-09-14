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
        // A concrete implementation is used instead of createMock(NormalizerInterface::class):
        // getSupportedTypes() is a real interface method on symfony/serializer 7.x but only a
        // documented, not formally declared, one on 6.4 — PHPUnit refuses to configure a mock
        // method that doesn't exist on the interface, so this must work under both.
        $customNormalizer = new class implements NormalizerInterface {
            /**
             * @return array<string, bool|null>
             */
            public function getSupportedTypes(?string $format): array
            {
                return ['*' => false];
            }

            public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
            {
                return true;
            }

            public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
            {
                return ['custom' => true];
            }
        };

        // Act
        $serializer = $builder->build([$customNormalizer]);
        $result = $serializer->normalize(new stdClass());

        // Assert
        $this->assertSame(['custom' => true], $result);
    }
}
