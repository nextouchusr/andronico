<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Nextouch\FastEst\Api\VanRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetVanList;

class VanRepository extends AbstractBaseRepository implements VanRepositoryInterface
{
    public function getAll(): GetVanList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->doRequest('get_van_ids', $request);

        return GetVanList::fromObject($result);
    }
}
