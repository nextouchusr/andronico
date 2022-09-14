<?php
declare(strict_types=1);

namespace Nextouch\Sales\Plugin\Model;

use Magento\Sales\Api\Data\ShipmentCommentCreationInterface;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsExtensionInterfaceFactory;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsInterface;
use Magento\Sales\Api\ShipOrderInterface;

class ForceEcommerceSourceCode
{
    private const ECOMMERCE_SORCE_CODE = 'ecommerce';

    private ShipmentCreationArgumentsExtensionInterfaceFactory $argumentsExtensionFactory;

    public function __construct(ShipmentCreationArgumentsExtensionInterfaceFactory $argumentsExtensionFactory)
    {
        $this->argumentsExtensionFactory = $argumentsExtensionFactory;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeExecute(
        ShipOrderInterface $subject,
        $orderId,
        array $items,
        bool $notify,
        bool $appendComment,
        ShipmentCommentCreationInterface $comment,
        array $tracks,
        array $packages,
        ShipmentCreationArgumentsInterface $arguments
    ): array {
        $extensionAttributes = $arguments->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->argumentsExtensionFactory->create();
        $sourceCode = $extensionAttributes->getSourceCode() ?: self::ECOMMERCE_SORCE_CODE;
        $extensionAttributes->setSourceCode($sourceCode);
        $arguments->setExtensionAttributes($extensionAttributes);

        return [
            $orderId,
            $items,
            $notify,
            $appendComment,
            $comment,
            $tracks,
            $packages,
            $arguments,
        ];
    }
}
