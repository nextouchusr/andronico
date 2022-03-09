<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Nextouch\FastEst\Api\HeadquarterRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetHeadquarterList;

class HeadquarterRepository extends AbstractBaseRepository implements HeadquarterRepositoryInterface
{
    public function getAll(): GetHeadquarterList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->doRequest('get_headquarter_ids', $request);

        return GetHeadquarterList::fromObject($result);
    }
}
