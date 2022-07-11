<?php
declare(strict_types=1);

namespace Nextouch\Rma\Plugin\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlInterface;
use Magento\Rma\Helper\Data;
use Magento\Sales\Api\Data\OrderInterface;

class ChangeReturnSubmitUrl
{
    private CustomerSession $customerSession;
    private UrlInterface $urlBuilder;

    public function __construct(
        CustomerSession $customerSession,
        UrlInterface $urlBuilder
    ) {
        $this->customerSession = $customerSession;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetReturnSubmitUrl(Data $subject, string $result, OrderInterface $order): string
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->getUrl('nextouch_rma/returns/submit', ['order_id' => $order->getId()]);
        }

        return $this->getUrl('nextouch_rma/guest/submit', ['order_id' => $order->getId()]);
    }

    private function getUrl($route, $params = []): string
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
