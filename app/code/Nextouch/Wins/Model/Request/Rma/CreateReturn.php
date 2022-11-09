<?php
declare(strict_types=1);

namespace Nextouch\Wins\Model\Request\Rma;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\App\ObjectManager;
use Magento\Rma\Api\Data\RmaInterface;
use Nextouch\Wins\Api\Data\InputInterface;
use Nextouch\Wins\Model\Auth\LoginInfo;
use function Lambdish\Phunctional\map;

class CreateReturn implements InputInterface
{
    private string $accessToken;
    private LoginInfo $loginInfo;
    private RmaInterface $return;

    public function __construct(
        string $accessToken,
        LoginInfo $loginInfo,
        RmaInterface $return
    ) {
        $this->accessToken = $accessToken;
        $this->loginInfo = $loginInfo;
        $this->return = $return;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getLoginInfo(): LoginInfo
    {
        return $this->loginInfo;
    }

    public function getReturn(): RmaInterface
    {
        return $this->return;
    }

    public function toArray(): array
    {
        $dataObjectConverter = ObjectManager::getInstance()->create(ExtensibleDataObjectConverter::class);
        $return = $dataObjectConverter->toNestedArray($this->getReturn(), [], RmaInterface::class);
        $return['comments'] = map(function (array $comment) {
            $customerNotified = $comment['customer_notified'] ?: false;

            return array_merge($comment, ['customer_notified' => $customerNotified]);
        }, $return['comments']);

        return array_merge(
            $return,
            ['login_info' => $this->getLoginInfo()->toArray()]
        );
    }
}
