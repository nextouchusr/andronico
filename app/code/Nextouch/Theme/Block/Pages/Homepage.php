<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Pages;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Nextouch\Theme\Block\BannerSlider\Widget;
use Nextouch\Theme\Helper\HomepageConfig;

class Homepage extends Template
{
    private const MAIN_SLIDER_NAME = 'main_homepage_slider';
    private const NEWS_SLIDER_NAME = 'news_homepage_slider';
    private const OFFERS_SLIDER_NAME = 'offers_homepage_slider';

    private HomepageConfig $config;

    public function __construct(Context $context, HomepageConfig $config, array $data = [])
    {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    public function getMainSlider(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(Widget::class)
                ->setSliderName(self::MAIN_SLIDER_NAME)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function getDecisionTreeUrl(): string
    {
        return $this->config->getDecisionTreeUrl();
    }

    public function getMainBannerImage(): string
    {
        return $this->config->getMainBannerImage();
    }

    public function getMainBannerUrl(): string
    {
        return $this->config->getMainBannerUrl();
    }

    public function getPromotionImage1(): string
    {
        return $this->config->getPromotionImage1();
    }

    public function getPromotionUrl1(): string
    {
        return $this->config->getPromotionUrl1();
    }

    public function getPromotionImage2(): string
    {
        return $this->config->getPromotionImage2();
    }

    public function getPromotionUrl2(): string
    {
        return $this->config->getPromotionUrl2();
    }

    public function getNewsSlider(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(Widget::class)
                ->setSliderName(self::NEWS_SLIDER_NAME)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function getOffersNavLinks(): array
    {
        try {
            /** @var Widget $slider */
            $slider = $this->getLayout()->createBlock(Widget::class)->setSliderName(self::OFFERS_SLIDER_NAME);

            return $slider->getBannerCollection()->getColumnValues('title');
        } catch (LocalizedException $e) {
            return [];
        }
    }

    public function getOffersSlider(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(Widget::class)
                ->setSliderName(self::OFFERS_SLIDER_NAME)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }
}
