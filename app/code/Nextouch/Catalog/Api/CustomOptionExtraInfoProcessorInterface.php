<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api;

use Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface;

/**
 * @api
 */
interface CustomOptionExtraInfoProcessorInterface
{
    /**
     * @param \Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface $customOption
     * @return \Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface
     */
    public function addCustomOptionExtraInfo(OptionInterface $customOption): OptionInterface;
}
