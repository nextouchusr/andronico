<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Plugin\Model;

use Magento\Catalog\Model\ProductOptionProcessorInterface;
use Nextouch\Catalog\Api\CustomOptionsExtraInfoProcessorInterface;

class AddCustomOptionsExtraInfo
{
    private CustomOptionsExtraInfoProcessorInterface $processor;

    public function __construct(CustomOptionsExtraInfoProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterConvertToProductOption(ProductOptionProcessorInterface $subject, array $result): array
    {
        if ($result) {
            $result['custom_options'] = $this->processor->addCustomOptionsExtraInfo($result['custom_options']);
        }

        return $result;
    }
}
