<?php

namespace Nextouch\GestLogis\Model;

use Magento\Framework\Model\AbstractModel;
use Nextouch\GestLogis\Model\ResourceModel\PostcodeServiceAttributes as PostcodeServiceAttributesResource;

class PostcodeServiceAttributes extends AbstractModel
{
    /**
     * _construct
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(PostcodeServiceAttributesResource::class);
    }
}
