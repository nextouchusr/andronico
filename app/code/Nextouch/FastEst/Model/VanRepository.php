<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\VanRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetVanList;

class VanRepository extends AbstractBaseRepository implements VanRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): GetVanList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->client->call('get_van_ids', $request->asObject());

        return GetVanList::fromObject($result);
    }
}
