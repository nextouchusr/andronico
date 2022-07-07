<?php
declare(strict_types=1);

namespace Nextouch\Wins\Api;

use Nextouch\Wins\Model\Request\Inventory\GetProductAvailability as GetProductAvailabilityRequest;
use Nextouch\Wins\Model\Response\Inventory\GetProductAvailability as GetProductAvailabilityResponse;

/**
 * @api
 */
interface ProductSalableQtyManagementInterface
{
    public function getProductAvailability(GetProductAvailabilityRequest $request): GetProductAvailabilityResponse;
}
