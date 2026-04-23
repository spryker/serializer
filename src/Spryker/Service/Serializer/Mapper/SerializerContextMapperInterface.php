<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Serializer\Mapper;

use Generated\Shared\Transfer\SerializerContextTransfer;

interface SerializerContextMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SerializerContextTransfer|null $serializerContextTransfer
     *
     * @return array<string, mixed>
     */
    public function mapSerializerContextTransferToContext(?SerializerContextTransfer $serializerContextTransfer): array;
}
