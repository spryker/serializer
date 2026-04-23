<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Serializer\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SerializerContextTransfer;
use Spryker\Service\Serializer\Mapper\SerializerContextMapper;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Serializer
 * @group Mapper
 * @group SerializerContextMapperTest
 * Add your own group annotations below this line
 */
class SerializerContextMapperTest extends Unit
{
    public function testGivenNullTransferWhenMapThenReturnsEmptyArray(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();

        // Act
        $context = $mapper->mapSerializerContextTransferToContext(null);

        // Assert
        $this->assertSame([], $context);
    }

    public function testGivenEmptyTransferWhenMapThenReturnsEmptyArray(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $transfer = new SerializerContextTransfer();

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertSame([], $context);
    }

    public function testGivenGroupsWhenMapThenContextContainsGroups(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $transfer = (new SerializerContextTransfer())
            ->addGroup('read')
            ->addGroup('list');

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertSame(['read', 'list'], $context[AbstractNormalizer::GROUPS]);
    }

    public function testGivenSkipNullValuesWhenMapThenContextContainsSkipNullValues(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $transfer = (new SerializerContextTransfer())
            ->setIsSkipNullValues(true);

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertTrue($context[AbstractObjectNormalizer::SKIP_NULL_VALUES]);
    }

    public function testGivenDatetimeFormatWhenMapThenContextContainsFormatKey(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $transfer = (new SerializerContextTransfer())
            ->setDatetimeFormat('Y-m-d');

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertSame('Y-m-d', $context[DateTimeNormalizer::FORMAT_KEY]);
    }

    public function testGivenConstructorArgumentsWhenMapThenContextContainsArguments(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $arguments = ['SomeClass' => ['arg1' => 'value1']];
        $transfer = (new SerializerContextTransfer())
            ->setDefaultConstructorArguments($arguments);

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertSame($arguments, $context[AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS]);
    }

    public function testGivenSinglePropertyWhenMapThenContextContainsOnlyThatProperty(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $transfer = (new SerializerContextTransfer())
            ->setIsSkipNullValues(true);

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertCount(1, $context);
        $this->assertArrayHasKey(AbstractObjectNormalizer::SKIP_NULL_VALUES, $context);
    }

    public function testGivenSymfonyContextOverrideWhenMapThenSymfonyContextWins(): void
    {
        // Arrange
        $mapper = new SerializerContextMapper();
        $transfer = (new SerializerContextTransfer())
            ->setIsSkipNullValues(true)
            ->setSymfonyContext([
                AbstractObjectNormalizer::SKIP_NULL_VALUES => false,
                'custom_key' => 'custom_value',
            ]);

        // Act
        $context = $mapper->mapSerializerContextTransferToContext($transfer);

        // Assert
        $this->assertFalse($context[AbstractObjectNormalizer::SKIP_NULL_VALUES]);
        $this->assertSame('custom_value', $context['custom_key']);
    }
}
