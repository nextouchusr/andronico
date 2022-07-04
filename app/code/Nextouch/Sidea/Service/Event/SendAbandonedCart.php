<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Service\Event;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Nextouch\Sidea\Api\AuthManagementInterface;
use Nextouch\Sidea\Api\EventManagementInterface;
use Nextouch\Sidea\Helper\SideaConfig;
use Nextouch\Sidea\Model\Event\AbandonedCart\Data;
use Nextouch\Sidea\Model\Request\Auth\Authorize;
use Nextouch\Sidea\Model\Request\Event\AbandonedCart;
use function Lambdish\Phunctional\each;

class SendAbandonedCart
{
    private AuthManagementInterface $authManagement;
    private EventManagementInterface $eventManagement;
    private CartRepositoryInterface $cartRepository;
    private SideaConfig $config;

    public function __construct(
        AuthManagementInterface $authManagement,
        EventManagementInterface $eventManagement,
        CartRepositoryInterface $cartRepository,
        SideaConfig $config
    ) {
        $this->authManagement = $authManagement;
        $this->eventManagement = $eventManagement;
        $this->cartRepository = $cartRepository;
        $this->config = $config;
    }

    public function execute(Quote $quote): void
    {
        $authRequest = new Authorize(
            $this->config->getAuthGrantType(),
            $this->config->getAuthClientId(),
            $this->config->getAuthClientSecret(),
            $this->config->getAuthAccountId()
        );

        $authResponse = $this->authManagement->authorize($authRequest);

        each(function (QuoteItem $quoteItem) use ($authResponse, $quote) {
            $request = new AbandonedCart(
                $authResponse->getAccessToken(),
                $quote->getCustomerEmail(),
                $this->config->getAbandonedCartEventKey(),
                Data::fromDomain($quoteItem)
            );

            $this->eventManagement->sendAbandonedCart($request);
        }, (array) $quote->getItems());
    }
}
