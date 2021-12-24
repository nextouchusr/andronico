<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\StatusRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetStatusList;

class StatusRepository extends AbstractBaseRepository implements StatusRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): GetStatusList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->client->call('get_status_ids', $request->asObject());

        return GetStatusList::fromObject($result);
    }
}
