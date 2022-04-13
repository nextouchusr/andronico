<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api;

use Nextouch\Sales\Api\Data\OrderInterface;

/**
 * @api
 */
interface OrderRepositoryInterface
{
    /**
     * @param string $incrementId
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIncrementId(string $incrementId): OrderInterface;
}
