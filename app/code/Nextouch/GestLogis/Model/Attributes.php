<?php

namespace Nextouch\GestLogis\Model;

use Magento\Framework\Model\AbstractModel;
use Nextouch\GestLogis\Model\ResourceModel\Attributes as AttributesResource;

class Attributes extends AbstractModel
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(AttributesResource::class);
    }
}
