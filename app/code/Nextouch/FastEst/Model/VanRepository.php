<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\VanRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\VanListResponse;

class VanRepository extends AbstractBaseRepository implements VanRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): VanListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_van_ids', $login->asObject());

        return VanListResponse::fromObject($result);
    }
}
