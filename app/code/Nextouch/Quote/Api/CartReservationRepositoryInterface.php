<?php
declare(strict_types=1);

namespace Nextouch\Quote\Api;

use Nextouch\Quote\Api\Data\CartInterface;

/**
 * @api
 */
interface CartReservationRepositoryInterface
{
    /**
     * @param string $reservedOrderId
     * @return \Nextouch\Quote\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByReservedOrderId(string $reservedOrderId): CartInterface;
}
