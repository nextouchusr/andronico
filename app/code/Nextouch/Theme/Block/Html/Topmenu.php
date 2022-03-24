<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Html;

use Magento\Framework\Data\Tree\Node;
use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\flat_map;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0): string
    {
        $newMenuItems = $this->getNewMenuItems();

        $this->removeAllMenuItems();
        $this->putNewMenuItems($newMenuItems);

        return parent::getHtml($outermostClass, $childrenWrapClass, $limit);
    }

    private function getNewMenuItems(): array
    {
        $menu = $this->getMenu();
        $firstLevel = $menu->getChildren();

        return flat_map(function (Node $menuItem) {
            if ($this->isCategoryMenu($menuItem)) {
                return $menuItem->getChildren();
            }

            return $menuItem;
        }, $firstLevel);
    }

    private function isCategoryMenu(Node $menuItem): bool
    {
        return substr($menuItem->getId(), 0, strlen('category-node-')) === 'category-node-';
    }

    private function removeAllMenuItems(): void
    {
        $menu = $this->getMenu();
        $firstLevel = $menu->getChildren();

        each(fn(Node $childNode) => $menu->removeChild($childNode), $firstLevel);
    }

    private function putNewMenuItems(array $newMenuItems): void
    {
        $menu = $this->getMenu();

        each(fn(Node $newMenuItem) => $menu->addChild($newMenuItem), $newMenuItems);
    }

    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit): string
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = [];
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '">';
        $html .= '<li class="level' . $childLevel . ' level-back "><span>';
        $html .= $this->escapeHtml($child->getName()) . '</span></li>';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        $html .= '</ul>';

        return $html;
    }
}
