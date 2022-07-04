<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Api;

use Nextouch\Sidea\Model\Request\Auth\Authorize as AuthorizeRequest;
use Nextouch\Sidea\Model\Response\Auth\Authorize as AuthorizeResponse;

/**
 * @api
 */
interface AuthManagementInterface
{
    public function authorize(AuthorizeRequest $request): AuthorizeResponse;
}
