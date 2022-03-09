<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api;

use Nextouch\Wins\Model\Request\Auth\Authorize as AuthorizeRequest;
use Nextouch\Wins\Model\Response\Auth\Authorize as AuthorizeResponse;

/**
 * @api
 */
interface AuthManagementInterface
{
    public function authorize(AuthorizeRequest $request): AuthorizeResponse;
}
