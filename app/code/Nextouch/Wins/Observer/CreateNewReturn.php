<?php
declare(strict_types=1);

namespace Nextouch\Wins\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Rma\Model\Rma;
use Nextouch\Wins\Service\Rma\CreateNewReturn as CreateNewReturnService;

class CreateNewReturn implements ObserverInterface
{
    private CreateNewReturnService $createNewReturnService;

    public function __construct(CreateNewReturnService $createNewReturnService)
    {
        $this->createNewReturnService = $createNewReturnService;
    }

    public function execute(Observer $observer): void
    {
        /** @var Rma $return */
        $return = $observer->getData('return');

        $this->createNewReturnService->create($return);
    }
}
