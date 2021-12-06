<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\ProductRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\ProductListResponse;

class ProductRepository extends AbstractBaseRepository implements ProductRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): ProductListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_product_ids', $login->asObject());

        return ProductListResponse::fromObject($result);
    }
}
