<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

/**
 * @api
 */
interface CustomOptionsExtraInfoProcessorInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\CustomOptionInterface[] $customOptions
     * @return \Magento\Catalog\Api\Data\CustomOptionInterface[]
     */
    public function addCustomOptionsExtraInfo(array $customOptions): array;
}
