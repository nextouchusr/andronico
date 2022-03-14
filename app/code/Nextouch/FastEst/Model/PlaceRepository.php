<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Nextouch\FastEst\Api\PlaceRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetPlaceList;

class PlaceRepository extends AbstractBaseRepository implements PlaceRepositoryInterface
{
    public function getAll(): GetPlaceList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->doRequest('get_place_ids', $request);

        return GetPlaceList::fromObject($result);
    }
}
