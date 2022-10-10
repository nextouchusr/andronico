<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Order;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\ObjectManager;
use Magento\Newsletter\Model\Subscriber;
use Magento\Paypal\Model\Config;
use Magento\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Order\InvoiceInfo;
use Nextouch\Wins\Model\Order\PickAndPayInfo;
use function Lambdish\Phunctional\any;

class CreateOrder implements InputInterface
{
    private string $accessToken;
    private LoginInfo $loginInfo;
    private OrderInterface $order;

    public function __construct(string $accessToken, LoginInfo $loginInfo, OrderInterface $order)
    {
        $this->accessToken = $accessToken;
        $this->loginInfo = $loginInfo;
        $this->order = $order;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getLoginInfo(): LoginInfo
    {
        return $this->loginInfo;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function toArray(): array
    {
        $invoiceInfo = InvoiceInfo::fromDomain($this->getOrder()->getBillingAddress());
        $pickAndPayInfo = PickAndPayInfo::fromDomain($this->getOrder());

        $dataObjectConverter = ObjectManager::getInstance()->create(ExtensibleDataObjectConverter::class);
        $order = $dataObjectConverter->toNestedArray($this->getOrder(), [], OrderInterface::class);
        $order = $this->prepareOrder($order);

        return array_merge(
            $order,
            ['invoice_info' => $invoiceInfo->toArray()],
            ['pick_and_pay_information' => $pickAndPayInfo->toArray()],
            ['login_info' => $this->getLoginInfo()->toArray()]
        );
    }

    private function prepareOrder(array $order): array
    {
        if ($order['customer_is_guest']) {
            $attributes = $order['amasty_order_attributes'] ?? [];
            $hasAmastyAttribute = function (string $code, array $attributes): bool {
                return any(fn(array $attr) => $attr['attribute_code'] === $code, $attributes);
            };

            $isPrivacyPolicyAccepted = $hasAmastyAttribute('is_privacy_policy_accepted', $attributes);
            $isSubscribed = $hasAmastyAttribute('is_subscribed', $attributes);
            $isWebProfilingAccepted = $hasAmastyAttribute('is_web_profiling_accepted', $attributes);
        } else {
            $customerRepository = ObjectManager::getInstance()->create(CustomerRepositoryInterface::class);
            $subscriber = ObjectManager::getInstance()->create(Subscriber::class);

            $customer = $customerRepository->getById($order['customer_id']);
            $checkSubscriber = $subscriber->loadByCustomerId($order['customer_id']);

            $isPrivacyPolicyAccepted = (bool) $customer->getCustomAttribute('is_privacy_policy_accepted');
            $isSubscribed = $checkSubscriber->isSubscribed();
            $isWebProfilingAccepted = (bool) $customer->getCustomAttribute('is_web_profiling_accepted');
        }

        $order['customer_is_privacy_policy_accepted'] = $isPrivacyPolicyAccepted;
        $order['customer_is_subscribed'] = $isSubscribed;
        $order['customer_is_web_profiling_accepted'] = $isWebProfilingAccepted;

        if ($order['payment']['method'] === Config::METHOD_EXPRESS) {
            $order['payment_additional_info'][] = [
                'key' => 'transaction_id',
                'value' => $order['payment']['last_trans_id'],
            ];

            $order['payment_additional_info'][] = [
                'key' => 'payment_id',
                'value' => $order['payment']['last_trans_id'],
            ];

            $order['payment_additional_info'][] = [
                'key' => 'method',
                'value' => $order['payment']['method'],
            ];
        }

        $order['extension_attributes'] = [
            'shipping_assignments' => $order['shipping_assignments'] ?? null,
            'payment_additional_info' => $order['payment_additional_info'] ?? null,
            'applied_taxes' => $order['applied_taxes'] ?? null,
            'item_applied_taxes' => $order['item_applied_taxes'] ?? null,
            'converting_from_quote' => $order['converting_from_quote'] ?? null,
            'gift_cards' => $order['gift_cards'] ?? null,
            'base_gift_cards_amount' => $order['base_gift_cards_amount'] ?? null,
            'gift_cards_amount' => $order['gift_cards_amount'] ?? null,
            'gw_base_price' => $order['gw_base_price'] ?? null,
            'gw_price' => $order['gw_price'] ?? null,
            'gw_items_base_price' => $order['gw_items_base_price'] ?? null,
            'gw_items_price' => $order['gw_items_price'] ?? null,
            'gw_card_base_price' => $order['gw_card_base_price'] ?? null,
            'gw_card_price' => $order['gw_card_price'] ?? null,
        ];

        return $order;
    }
}
