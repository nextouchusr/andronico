<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\BannerSlider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Widget\Block\BlockInterface;
use Mageplaza\BannerSlider\Model\Slider;

/**
 * @method getSliderName()
 * @method setSliderName(string $name)
 * @method setSlider(Slider $slider)
 */
class Widget extends \Mageplaza\BannerSlider\Block\Slider implements BlockInterface
{
    /**
     * @throws NoSuchEntityException
     */
    public function getBannerCollection()
    {
        $name = $this->getSliderName();

        /** @var Slider $slider */
        $slider = $this->helperData
            ->getActiveSliders()
            ->addFieldToFilter('name', $name)
            ->getFirstItem();

        if (!$slider->getId()) {
            throw new NoSuchEntityException(__('The slider %1 that was requested does not exist.', $name));
        }

        $this->setSlider($slider);

        return parent::getBannerCollection();
    }
}
