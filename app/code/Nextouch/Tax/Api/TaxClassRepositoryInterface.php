<?php
declare(strict_types=1);

namespace Nextouch\Tax\Api;

use Magento\Tax\Api\Data\TaxClassInterface;

/**
 * @api
 */
interface TaxClassRepositoryInterface
{
    /**
     * @param string $name
     * @param string $type
     * @return \Magento\Tax\Api\Data\TaxClassInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByName(string $name, string $type): TaxClassInterface;
}
