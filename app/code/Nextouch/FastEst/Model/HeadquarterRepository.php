<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\HeadquarterRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\HeadquarterListResponse;

class HeadquarterRepository extends AbstractBaseRepository implements HeadquarterRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): HeadquarterListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_headquarter_ids', $login->asObject());

        return HeadquarterListResponse::fromObject($result);
    }
}
