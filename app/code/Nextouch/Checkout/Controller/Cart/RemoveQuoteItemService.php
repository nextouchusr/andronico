<?php
declare(strict_types=1);

namespace Nextouch\Checkout\Controller\Cart;

use Magento\Checkout\Controller\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\OptionFactory;
use Magento\Store\Model\StoreManagerInterface;
use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\search;

class RemoveQuoteItemService extends Cart
{
    private const SERVICE_SEPARATOR = ',';

    private OptionFactory $optionFactory;
    private CartRepositoryInterface $cartRepository;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart,
        OptionFactory $optionFactory,
        CartRepositoryInterface $cartRepository
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->optionFactory = $optionFactory;
        $this->cartRepository = $cartRepository;
    }

    private function getQuoteItem(): ?Item
    {
        $itemId = $this->getRequest()->getParam('itemId');

        return search(fn(Item $item) => $item->getId() === $itemId, $this->cart->getItems());
    }

    private function getSelectedServices(): string
    {
        $item = $this->getQuoteItem();
        $optionId = $this->getRequest()->getParam('optionId');
        $serviceId = $this->getRequest()->getParam('serviceId');

        $option = $item->getOptionByCode('option_' . $optionId);
        $currentServiceIds = explode(self::SERVICE_SEPARATOR, $option->getValue());
        $selectedServiceIds = filter(fn(string $id) => $id !== $serviceId, $currentServiceIds);

        return implode(self::SERVICE_SEPARATOR, $selectedServiceIds);
    }

    private function hasSelectedServices(): bool
    {
        return strlen($this->getSelectedServices()) > 0;
    }

    public function execute()
    {
        if ($this->shouldRemoveAllService()) {
            $this->removeAllServices();
        } else {
            $this->removeSingleService();
        }

        return $this->_redirect('checkout/cart');
    }

    private function shouldRemoveAllService(): bool
    {
        /** @var Http $request */
        $request = $this->getRequest();
        $item = $this->getQuoteItem();

        return $request->isPost() && $item && !$this->hasSelectedServices();
    }

    private function removeAllServices(): void
    {
        $item = $this->getQuoteItem();
        $optionId = $this->getRequest()->getParam('optionId');

        $item->removeOption('option_' . $optionId);
        $item->removeOption('option_ids');
        $item->saveItemOptions();

        $this->recalculateTotals();
    }

    private function removeSingleService(): void
    {
        $item = $this->getQuoteItem();
        $optionId = $this->getRequest()->getParam('optionId');
        $value = $this->getSelectedServices();

        $item->removeOption('option_' . $optionId);
        $item->saveItemOptions();

        $option = $this->optionFactory->create();
        $option->setItem($item);
        $option->setProduct($item->getProduct());
        $option->setCode('option_' . $optionId);
        $option->setValue($value);

        $item->setOptions([$option]);
        $item->saveItemOptions();

        $this->recalculateTotals();
    }

    private function recalculateTotals(): void
    {
        $quote = $this->cart->getQuote();
        $quote->collectTotals();
        $this->cartRepository->save($quote);
    }
}
