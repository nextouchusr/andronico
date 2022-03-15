<?php
declare(strict_types=1);

namespace Nextouch\Tax\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Tax\Api\Data\TaxClassInterface;
use Magento\Tax\Model\ClassModel;
use Magento\Tax\Model\ResourceModel\TaxClass\CollectionFactory as TaxClassCollectionFactory;
use Nextouch\Tax\Api\TaxClassRepositoryInterface;

class TaxClassRepository implements TaxClassRepositoryInterface
{
    private TaxClassCollectionFactory $taxClassCollectionFactory;

    public function __construct(TaxClassCollectionFactory $taxClassCollectionFactory)
    {
        $this->taxClassCollectionFactory = $taxClassCollectionFactory;
    }

    public function getByName(string $name, string $type): TaxClassInterface
    {
        /** @var TaxClassInterface $taxClass */
        $taxClass = $this->taxClassCollectionFactory
            ->create()
            ->addFieldToFilter(ClassModel::KEY_NAME, $name)
            ->addFieldToFilter(ClassModel::KEY_TYPE, $type)
            ->getFirstItem();

        if (!$taxClass->getClassId()) {
            throw new NoSuchEntityException(__('The tax class that was requested does not exist.'));
        }

        return $taxClass;
    }
}
