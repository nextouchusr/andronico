<?php
declare(strict_types=1);

namespace Nextouch\Rma\Api;

use Nextouch\Rma\Api\Data\RmaInterface;

/**
 * @api
 */
interface RmaRepositoryInterface
{
    /**
     * @param int $id
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id): RmaInterface;

    /**
     * @param string $incrementId
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIncrementId(string $incrementId): RmaInterface;

    /**
     * @param \Nextouch\Rma\Api\Data\RmaInterface $return
     * @return \Nextouch\Rma\Api\Data\RmaInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(RmaInterface $return): RmaInterface;
}
