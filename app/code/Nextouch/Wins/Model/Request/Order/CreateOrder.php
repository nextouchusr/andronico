<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Order;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\ObjectManager;
use Nextouch\Sales\Api\Data\OrderInterface;
use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Order\InvoiceInfo;
use Nextouch\Wins\Model\Order\PickAndPayInfo;

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
        $dataObjectConverter = ObjectManager::getInstance()->create(ExtensibleDataObjectConverter::class);

        return array_merge(
            $dataObjectConverter->toNestedArray($this->getOrder(), [], OrderInterface::class),
            ['invoice_info' => InvoiceInfo::fromDomain($this->getOrder()->getBillingAddress())],
            ['pick_and_pay_information' => PickAndPayInfo::fromDomain($this->getOrder())],
            ['login_info' => $this->getLoginInfo()->toArray()]
        );
    }
}
