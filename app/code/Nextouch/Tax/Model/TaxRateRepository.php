<?php
declare(strict_types=1);

namespace Nextouch\Tax\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Tax\Api\Data\TaxRateInterface;
use Magento\Tax\Model\Calculation\Rate;
use Magento\Tax\Model\ResourceModel\Calculation\Rate\CollectionFactory as TaxRateCollectionFactory;
use Nextouch\Tax\Api\TaxRateRepositoryInterface;

class TaxRateRepository implements TaxRateRepositoryInterface
{
    private TaxRateCollectionFactory $taxRateCollectionFactory;

    public function __construct(TaxRateCollectionFactory $taxRateCollectionFactory)
    {
        $this->taxRateCollectionFactory = $taxRateCollectionFactory;
    }

    public function getByCode(string $code): TaxRateInterface
    {
        /** @var TaxRateInterface $taxRate */
        $taxRate = $this->taxRateCollectionFactory
            ->create()
            ->addFieldToFilter(Rate::KEY_CODE, $code)
            ->getFirstItem();

        if (!$taxRate->getId()) {
            throw new NoSuchEntityException(__('The tax rate that was requested does not exist.'));
        }

        return $taxRate;
    }
}
