<?php
declare(strict_types=1);

namespace Nextouch\Sales\Api;

/**
 * @api
 */
interface OrderManagementInterface
{
    /**
     * @param string $incrementId
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function inCharge(string $incrementId): int;

    /**
     * @param string $incrementId
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function scheduled(string $incrementId): int;

    /**
     * @param string $incrementId
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function partiallyComplete(string $incrementId): int;

    /**
     * @param string $incrementId
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function complete(string $incrementId): int;
}
