<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\HourRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\HourListResponse;

class HourRepository extends AbstractBaseRepository implements HourRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): HourListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_hour_ids', $login->asObject());

        return HourListResponse::fromObject($result);
    }
}
