<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Api;

use Nextouch\Findomestic\Model\Request\Installment\Activate as ActivateRequest;
use Nextouch\Findomestic\Model\Request\Installment\Cancel as CancelRequest;
use Nextouch\Findomestic\Model\Request\Installment\Create as CreateRequest;
use Nextouch\Findomestic\Model\Request\Installment\Refresh as RefreshRequest;
use Nextouch\Findomestic\Model\Response\Installment\Activate as ActivateResponse;
use Nextouch\Findomestic\Model\Response\Installment\Cancel as CancelResponse;
use Nextouch\Findomestic\Model\Response\Installment\Create as CreateResponse;
use Nextouch\Findomestic\Model\Response\Installment\Refresh as RefreshResponse;

/**
 * @api
 */
interface InstallmentManagementInterface
{
    /**
     * @param \Nextouch\Findomestic\Model\Request\Installment\Create $request
     * @return \Nextouch\Findomestic\Model\Response\Installment\Create
     */
    public function create(CreateRequest $request): CreateResponse;

    /**
     * @param \Nextouch\Findomestic\Model\Request\Installment\Refresh $request
     * @return \Nextouch\Findomestic\Model\Response\Installment\Refresh
     */
    public function refresh(RefreshRequest $request): RefreshResponse;

    /**
     * @param \Nextouch\Findomestic\Model\Request\Installment\Activate $request
     * @return \Nextouch\Findomestic\Model\Response\Installment\Activate
     */
    public function activate(ActivateRequest $request): ActivateResponse;

    /**
     * @param \Nextouch\Findomestic\Model\Request\Installment\Cancel $request
     * @return \Nextouch\Findomestic\Model\Response\Installment\Cancel
     */
    public function cancel(CancelRequest $request): CancelResponse;
}
