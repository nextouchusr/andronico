<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Pages;

use Magento\Cms\Block\BlockByIdentifier;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

class Homepage extends Template
{
    private const HERO_SLIDER_BLOCK_ID = 'homepage_hero_slider';
    private const DECISION_TREE_BLOCK_ID = 'homepage_decision_tree';
    private const MAIN_BANNER_BLOCK_ID = 'homepage_main_banner';
    private const PROMOTIONS_BLOCK_ID = 'homepage_promotions';
    private const NEWS_BLOCK_ID = 'homepage_news';
    private const OFFERS_BLOCK_ID = 'homepage_offers';
    private const HELPDESK_BLOCK_ID = 'homepage_helpdesk';

    public function createHeroSliderBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::HERO_SLIDER_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function createDecisionTreeBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::DECISION_TREE_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function createMainBannerBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::MAIN_BANNER_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function createPromotionsBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::PROMOTIONS_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function createNewsBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::NEWS_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function createOffersBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::OFFERS_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }

    public function createHelpdeskBlock(): string
    {
        try {
            return $this->getLayout()
                ->createBlock(BlockByIdentifier::class)
                ->setIdentifier(self::HELPDESK_BLOCK_ID)
                ->toHtml();
        } catch (LocalizedException $e) {
            return '';
        }
    }
}
