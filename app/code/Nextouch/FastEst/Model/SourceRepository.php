<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model;

use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\SourceRepositoryInterface;
use Nextouch\FastEst\Model\Common\Login;
use Nextouch\FastEst\Model\Request\Directory\GetDirectoryList;
use Nextouch\FastEst\Model\Response\Directory\GetSourceList;

class SourceRepository extends AbstractBaseRepository implements SourceRepositoryInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function getAll(): GetSourceList
    {
        $username = $this->config->getUsername($this->scopeCode);
        $password = $this->config->getPassword($this->scopeCode);

        $login = new Login($username, $password);
        $request = new GetDirectoryList($login);

        $result = $this->client->call('get_source_ids', $request->asObject());

        return GetSourceList::fromObject($result);
    }
}
