<?php
declare(strict_types=1);

namespace Nextouch\Sales\Plugin\Model;

use Magento\Sales\Api\Data\ShipmentCommentCreationInterface;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsExtensionInterfaceFactory;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsInterface;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsInterfaceFactory;
use Magento\Sales\Api\ShipOrderInterface;

class ForceEcommerceSourceCode
{
    private const ECOMMERCE_SORCE_CODE = 'ecommerce';

    private ShipmentCreationArgumentsInterfaceFactory $argumentsFactory;
    private ShipmentCreationArgumentsExtensionInterfaceFactory $argumentsExtensionFactory;

    public function __construct(
        ShipmentCreationArgumentsInterfaceFactory $argumentsFactory,
        ShipmentCreationArgumentsExtensionInterfaceFactory $argumentsExtensionFactory
    ) {
        $this->argumentsFactory = $argumentsFactory;
        $this->argumentsExtensionFactory = $argumentsExtensionFactory;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeExecute(
        ShipOrderInterface $subject,
        $orderId,
        array $items,
        bool $notify = false,
        bool $appendComment = false,
        ShipmentCommentCreationInterface $comment = null,
        array $tracks = [],
        array $packages = [],
        ShipmentCreationArgumentsInterface $arguments = null
    ): array {
        $arguments = $arguments ?: $this->argumentsFactory->create();
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
