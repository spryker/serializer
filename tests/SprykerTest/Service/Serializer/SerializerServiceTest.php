<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Serializer;

use Codeception\Test\Unit;
use Spryker\Service\Serializer\SerializerDependencyProvider;
use Spryker\Service\Serializer\SerializerService;
use Spryker\Shared\SerializerExtension\Dependency\Plugin\SerializerNormalizerPluginInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Serializer
 * @group SerializerServiceTest
 * Add your own group annotations below this line
 */
class SerializerServiceTest extends Unit
{
    protected SerializerServiceTester $tester;

    public function testGivenArrayDataWhenSerializeThenReturnsJsonString(): void
    {
        // Arrange
        $service = $this->getSerializerService();
        $data = ['key' => 'value'];

        // Act
        $result = $service->serialize($data, 'json');

        // Assert
        $this->assertSame('{"key":"value"}', $result);
    }

    public function testGivenJsonStringWhenDeserializeThenReturnsTypedObject(): void
    {
        // Arrange
        $service = $this->getSerializerService();
        $json = '{"name":"test","value":42}';

        // Act
        $result = $service->deserialize($json, SerializerTestDto::class, 'json');

        // Assert
        $this->assertInstanceOf(SerializerTestDto::class, $result);
        $this->assertSame('test', $result->name);
    }

    public function testGivenObjectWhenNormalizeThenReturnsArray(): void
    {
        // Arrange
        $service = $this->getSerializerService();
        $dto = new SerializerTestDto();
        $dto->name = 'test';
        $dto->value = 1;

        // Act
        $result = $service->normalize($dto);

        // Assert
        $this->assertIsArray($result);
        $this->assertSame('test', $result['name']);
    }

    public function testGivenArrayWhenDenormalizeThenReturnsTypedObject(): void
    {
        // Arrange
        $service = $this->getSerializerService();

        // Act
        $result = $service->denormalize(['name' => 'test', 'value' => 1], SerializerTestDto::class);

        // Assert
        $this->assertInstanceOf(SerializerTestDto::class, $result);
        $this->assertSame('test', $result->name);
    }

    public function testGivenCustomNormalizerPluginWhenNormalizeThenUsesCustomNormalizer(): void
    {
        // Arrange
        $customNormalizer = new class implements NormalizerInterface, DenormalizerInterface {
            public function normalize(mixed $data, ?string $format = null, array $context = []): array
            {
                return ['custom' => true];
            }

            public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
            {
                return $data instanceof SerializerTestDto;
            }

            public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
            {
                return new SerializerTestDto();
            }

            public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
            {
                return false;
            }

            public function getSupportedTypes(?string $format): array
            {
                return [SerializerTestDto::class => false];
            }
        };

        $normalizerPlugin = $this->createMock(SerializerNormalizerPluginInterface::class);
        $normalizerPlugin->method('getNormalizers')
            ->willReturn([$customNormalizer]);

        $this->tester->setDependency(
            SerializerDependencyProvider::PLUGINS_SERIALIZER_NORMALIZER,
            [$normalizerPlugin],
        );

        $service = $this->getSerializerService();
        $dto = new SerializerTestDto();
        $dto->name = 'test';

        // Act
        $result = $service->normalize($dto);

        // Assert
        $this->assertSame(['custom' => true], $result);
    }

    protected function getSerializerService(): SerializerService
    {
        return $this->tester->getLocator()->serializer()->service();
    }
}
