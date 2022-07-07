<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api;

/**
 * @api
 */
interface OrderManagementInterface
{
    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function inDelivery(int $id): bool;

    /**
     * @param string $incrementId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function inDeliveryByIncrementId(string $incrementId): bool;

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function deliver(int $id): bool;

    /**
     * @param string $incrementId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function deliverByIncrementId(string $incrementId): bool;
}
