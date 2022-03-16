<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\Block\MegaMenuLite;

use Amasty\MegaMenuLite\Block\Container;
use Magento\Framework\Data\Tree\Node;
use function Lambdish\Phunctional\first;

class ChangeRootMenuCategory
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetMenuTree(Container $subject, ?Node $result): ?Node
    {
        if ($result->getChildren()->count()) {
            return first($result->getChildren()->getNodes());
        }

        return $result;
    }
}
