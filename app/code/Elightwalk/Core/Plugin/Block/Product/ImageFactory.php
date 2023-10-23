<?php

namespace Elightwalk\Core\Plugin\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Amasty\GiftCard\Model\Image\Repository;
use Amasty\GiftCard\Utils\FileUpload;

class ImageFactory
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var Repository
     */
    protected $_imageRepository;

    /**
     * @var FileUpload
     */
    protected $_fileUpload;

    /**
     * __construct
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Repository $imageRepository
     * @param FileUpload $fileUpload
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Repository $imageRepository,
        FileUpload $fileUpload
    ) {
        $this->_productRepository = $productRepository;
        $this->_imageRepository   = $imageRepository;
        $this->_fileUpload        = $fileUpload;
    }

    /**
     * AfterCreate
     *
     * @param \Magento\Catalog\Block\Product\ImageFactory $subject
     * @param object $result
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array|null $attributes
     * @return object
     */
    public function afterCreate(
        \Magento\Catalog\Block\Product\ImageFactory $subject,
        $result,
        \Magento\Catalog\Model\Product $product,
        string $imageId,
        array $attributes = null
    ) {
        if ($product && $product->getTypeId() == 'amgiftcard' && !$product->getImageId()) {
            $_product = $this->_productRepository->getById($product->getId());

            if ($_product->getAmGiftcardCodeImage()) {
                $image = $this->_imageRepository->getById($_product->getAmGiftcardCodeImage());
                $src   = $this->_fileUpload->getImageUrl($image->getImagePath());

                $result->setData('image_url', $src);
            }
        }

        return $result;
    }
}
