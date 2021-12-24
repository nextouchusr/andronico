<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\HourRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetHourList;

class HourRepository extends AbstractBaseRepository implements HourRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): GetHourList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->client->call('get_hour_ids', $request->asObject());

        return GetHourList::fromObject($result);
    }
}
