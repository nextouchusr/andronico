<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\SourceRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Response\Directory\SourceListResponse;

class SourceRepository extends AbstractBaseRepository implements SourceRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): SourceListResponse
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);
        $login = new Login($username, $password);

        $result = $this->client->call('get_source_ids', $login->asObject());

        return SourceListResponse::fromObject($result);
    }
}
