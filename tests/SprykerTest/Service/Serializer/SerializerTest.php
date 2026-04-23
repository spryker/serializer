<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Serializer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SerializerContextTransfer;
use Spryker\Service\Serializer\SerializerInterface;
use Spryker\Service\Serializer\SerializerServiceFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Serializer
 * @group SerializerTest
 * Add your own group annotations below this line
 */
class SerializerTest extends Unit
{
    protected SerializerServiceTester $tester;

    protected function getSerializer(): SerializerInterface
    {
        return (new SerializerServiceFactory())->createSerializer();
    }

    public function testGivenArrayDataWhenSerializeThenReturnsJsonString(): void
    {
        // Arrange
        $serializer = $this->getSerializer();
        $data = ['name' => 'test', 'value' => 42];

        // Act
        $result = $serializer->serialize($data, 'json');

        // Assert
        $this->assertSame('{"name":"test","value":42}', $result);
    }

    public function testGivenJsonStringWhenDeserializeThenReturnsTypedObject(): void
    {
        // Arrange
        $serializer = $this->getSerializer();
        $json = '{"name":"test","value":42}';

        // Act
        $result = $serializer->deserialize($json, SerializerTestDto::class, 'json');

        // Assert
        $this->assertInstanceOf(SerializerTestDto::class, $result);
        $this->assertSame('test', $result->name);
        $this->assertSame(42, $result->value);
    }

    public function testGivenObjectWhenNormalizeThenReturnsArray(): void
    {
        // Arrange
        $serializer = $this->getSerializer();
        $dto = new SerializerTestDto();
        $dto->name = 'test';
        $dto->value = 42;

        // Act
        $result = $serializer->normalize($dto);

        // Assert
        $this->assertSame('test', $result['name']);
        $this->assertSame(42, $result['value']);
    }

    public function testGivenArrayWhenDenormalizeThenReturnsTypedObject(): void
    {
        // Arrange
        $serializer = $this->getSerializer();
        $data = ['name' => 'test', 'value' => 42];

        // Act
        $result = $serializer->denormalize($data, SerializerTestDto::class);

        // Assert
        $this->assertInstanceOf(SerializerTestDto::class, $result);
        $this->assertSame('test', $result->name);
        $this->assertSame(42, $result->value);
    }

    public function testGivenExistingObjectWhenDenormalizeWithObjectToPopulateThenHydratesExistingObject(): void
    {
        // Arrange
        $serializer = $this->getSerializer();
        $existingDto = new SerializerTestDto();
        $existingDto->name = 'original';
        $existingDto->value = 0;

        // Act
        $result = $serializer->denormalize(
            ['name' => 'updated', 'value' => 99],
            SerializerTestDto::class,
            null,
            null,
            $existingDto,
        );

        // Assert
        $this->assertSame($existingDto, $result);
        $this->assertSame('updated', $result->name);
        $this->assertSame(99, $result->value);
    }

    public function testGivenNullPropertyWhenSerializeWithSkipNullContextThenOmitsNullValues(): void
    {
        // Arrange
        $serializer = $this->getSerializer();
        $dto = new SerializerTestDto();
        $dto->name = 'test';
        $dto->value = null;

        $contextTransfer = (new SerializerContextTransfer())
            ->setIsSkipNullValues(true);

        // Act
        $result = $serializer->serialize($dto, 'json', $contextTransfer);

        // Assert
        $decoded = json_decode($result, true);
        $this->assertArrayHasKey('name', $decoded);
        $this->assertArrayNotHasKey('value', $decoded);
    }
}
