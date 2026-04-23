<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer\Mapper;

use Generated\Shared\Transfer\SerializerContextTransfer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class SerializerContextMapper implements SerializerContextMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function mapSerializerContextTransferToContext(?SerializerContextTransfer $serializerContextTransfer): array
    {
        if ($serializerContextTransfer === null) {
            return [];
        }

        $context = [];

        $this->mapGroups($serializerContextTransfer, $context);
        $this->mapBooleanOptions($serializerContextTransfer, $context);
        $this->mapScalarOptions($serializerContextTransfer, $context);
        $this->mapArrayOptions($serializerContextTransfer, $context);
        $this->mergeSymfonyContext($serializerContextTransfer, $context);

        return $context;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function mapGroups(SerializerContextTransfer $serializerContextTransfer, array &$context): void
    {
        if ($serializerContextTransfer->getGroups() === []) {
            return;
        }

        $context[AbstractNormalizer::GROUPS] = $serializerContextTransfer->getGroups();
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function mapBooleanOptions(SerializerContextTransfer $serializerContextTransfer, array &$context): void
    {
        $booleanMappings = [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => $serializerContextTransfer->getIsSkipNullValues(),
            AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => $serializerContextTransfer->getIsSkipUninitializedValues(),
            AbstractObjectNormalizer::PRESERVE_EMPTY_OBJECTS => $serializerContextTransfer->getIsPreserveEmptyObjects(),
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => $serializerContextTransfer->getIsEnableMaxDepth(),
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => $serializerContextTransfer->getIsAllowExtraAttributes(),
            AbstractObjectNormalizer::COLLECT_DENORMALIZATION_ERRORS => $serializerContextTransfer->getIsCollectDenormalizationErrors(),
            AbstractObjectNormalizer::REQUIRE_ALL_PROPERTIES => $serializerContextTransfer->getIsRequireAllProperties(),
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => $serializerContextTransfer->getIsDisableTypeEnforcement(),
        ];

        foreach ($booleanMappings as $contextKey => $value) {
            if ($value === null) {
                continue;
            }

            $context[$contextKey] = $value;
        }
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function mapScalarOptions(SerializerContextTransfer $serializerContextTransfer, array &$context): void
    {
        if ($serializerContextTransfer->getMaxDepth() !== null && $serializerContextTransfer->getIsEnableMaxDepth() !== false) {
            $context[AbstractObjectNormalizer::ENABLE_MAX_DEPTH] = true;
        }

        if ($serializerContextTransfer->getDatetimeFormat() !== null) {
            $context[DateTimeNormalizer::FORMAT_KEY] = $serializerContextTransfer->getDatetimeFormat();
        }

        if ($serializerContextTransfer->getDatetimeTimezone() !== null) {
            $context[DateTimeNormalizer::TIMEZONE_KEY] = $serializerContextTransfer->getDatetimeTimezone();
        }
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function mapArrayOptions(SerializerContextTransfer $serializerContextTransfer, array &$context): void
    {
        if ($serializerContextTransfer->getDefaultConstructorArguments() !== []) {
            $context[AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS] = $serializerContextTransfer->getDefaultConstructorArguments();
        }
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return void
     */
    protected function mergeSymfonyContext(SerializerContextTransfer $serializerContextTransfer, array &$context): void
    {
        if ($serializerContextTransfer->getSymfonyContext() === []) {
            return;
        }

        /** @var array<string, mixed> $symfonyContext */
        $symfonyContext = $serializerContextTransfer->getSymfonyContext();
        $context = array_merge($context, $symfonyContext);
    }
}
