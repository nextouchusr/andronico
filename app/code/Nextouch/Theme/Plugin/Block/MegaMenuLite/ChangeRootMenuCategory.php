<?php
declare(strict_types=1);

namespace Nextouch\Theme\Plugin\Block\MegaMenuLite;

use Amasty\MegaMenuLite\Block\Container;

class ChangeRootMenuCategory
{
    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGetAllNodesData(Container $subject, array $result): array
    {
        if ($result) {
            return $result[0]['elems'];
        }

        return $result;
    }
}
