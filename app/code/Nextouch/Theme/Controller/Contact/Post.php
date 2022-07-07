<?php
declare(strict_types=1);

namespace Nextouch\Theme\Controller\Contact;

class Post extends \Magento\Contact\Controller\Index\Post
{
    public function execute()
    {
        parent::execute();

        return $this->resultRedirectFactory->create()->setPath('assistance-service');
    }
}
