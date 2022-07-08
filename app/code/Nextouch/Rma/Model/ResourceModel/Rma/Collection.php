<?php
declare(strict_types=1);

namespace Nextouch\Rma\Model\ResourceModel\Rma;

use Magento\Rma\Model\ResourceModel\Rma as RmaResourceModel;
use Magento\Rma\Model\ResourceModel\Rma\Collection as RmaCollection;
use Nextouch\Rma\Model\Rma;

class Collection extends RmaCollection
{
    protected function _construct(): void
    {
        $this->_init(Rma::class, RmaResourceModel::class);
    }
}
