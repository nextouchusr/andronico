<?php
declare(strict_types=1);

namespace Nextouch\Theme\Block\Catalog\Product;

use Magento\Catalog\Block\Product\View\Gallery;

class View extends \Magento\Catalog\Block\Product\View
{
    public function getStickyImageUrl(): string
    {
        $galleryBlock = $this->getGalleryBlock();

        $images = $galleryBlock->getGalleryImages()->getItems();
        $mainImage = current(array_filter($images, fn($img) => $galleryBlock->isMainImage($img)));

        if (!empty($images) && empty($mainImage)) {
            $mainImage = $galleryBlock->getGalleryImages()->getFirstItem();
        }

        $helper = $galleryBlock->getData('imageHelper');

        if ($mainImage) {
            return (string) $mainImage->getData('small_image_url');
        }

        return (string) $helper->getDefaultPlaceholderUrl('image');
    }

    private function getGalleryBlock(): Gallery
    {
        /** @var Gallery $block */
        $block = $this->getLayout()->getBlock('product.info.media.image');

        return $block;
    }
}
