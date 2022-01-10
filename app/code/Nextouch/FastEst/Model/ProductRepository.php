<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Nextouch\FastEst\Api\ProductRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetProductList;

class ProductRepository extends AbstractBaseRepository implements ProductRepositoryInterface
{
    public function getAll(): GetProductList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->client->call('get_product_ids', $request->asObject());

        return GetProductList::fromObject($result);
    }
}
