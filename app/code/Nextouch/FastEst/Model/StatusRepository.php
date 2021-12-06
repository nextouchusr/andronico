<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\StatusRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\StatusListResponse;

class StatusRepository extends AbstractBaseRepository implements StatusRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): StatusListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_status_ids', $login->asObject());

        return StatusListResponse::fromObject($result);
    }
}
