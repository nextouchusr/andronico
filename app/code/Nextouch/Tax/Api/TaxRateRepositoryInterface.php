<?php
declare(strict_types=1);

namespace Nextouch\Tax\Api;

use Magento\Tax\Api\Data\TaxRateInterface;

/**
 * @api
 */
interface TaxRateRepositoryInterface
{
    /**
     * @param string $code
     * @return \Magento\Tax\Api\Data\TaxRateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByCode(string $code): TaxRateInterface;
}
