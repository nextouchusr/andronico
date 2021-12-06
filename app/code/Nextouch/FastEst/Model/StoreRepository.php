<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\StoreRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\StoreListResponse;

class StoreRepository extends AbstractBaseRepository implements StoreRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): StoreListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_store_ids', $login->asObject());

        return StoreListResponse::fromObject($result);
    }
}
