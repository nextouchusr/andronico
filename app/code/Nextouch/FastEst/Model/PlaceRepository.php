<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\PlaceRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\PlaceListResponse;

class PlaceRepository extends AbstractBaseRepository implements PlaceRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): PlaceListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_place_ids', $login->asObject());

        return PlaceListResponse::fromObject($result);
    }
}
