<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\Block\CatalogSearch\Result;

use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\CatalogSearch\Block\Result;

class EditAvailableSortOptions
{
    protected Layer $layer;

    public function __construct(Resolver $resolver)
    {
        $this->layer = $resolver->get();
    }

    public function afterSetListOrders(Result $subject): Result
    {
        $category = $this->layer->getCurrentCategory();
        $availableOrders = $category->getAvailableSortByOptions();
        unset($availableOrders['position']);

        $subject
            ->getListBlock()
            ->setAvailableOrders($availableOrders)
            ->setDefaultDirection('desc')
            ->setDefaultSortBy($category->getDefaultSortBy());

        return $subject;
    }
}
