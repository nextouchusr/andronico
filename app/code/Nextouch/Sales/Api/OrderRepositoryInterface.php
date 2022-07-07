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
     * @param int $id
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id): OrderInterface;

    /**
     * @param string $incrementId
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIncrementId(string $incrementId): OrderInterface;

    /**
     * @param \Nextouch\Sales\Api\Data\OrderInterface $order
     * @return \Nextouch\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(OrderInterface $order): OrderInterface;
}
