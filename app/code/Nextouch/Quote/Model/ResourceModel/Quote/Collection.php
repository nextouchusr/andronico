<?php
declare(strict_types=1);

namespace Nextouch\Quote\Model\ResourceModel\Quote;

use Magento\Quote\Model\ResourceModel\Quote as QuoteResourceModel;
use Magento\Quote\Model\ResourceModel\Quote\Collection as QuoteCollection;
use Nextouch\Quote\Model\Quote;

class Collection extends QuoteCollection
{
    protected function _construct(): void
    {
        $this->_init(Quote::class, QuoteResourceModel::class);
    }
}
