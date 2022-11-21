<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elightwalk\Core\Helper;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Image;

/**
 * Customer helper for view.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Product
     */
    protected $productModel;

    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * __construct
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param Product $productModel
     * @param Image $imageHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Product $productModel,
        Image $imageHelper
    ) {
        parent::__construct($context);

        $this->productModel = $productModel;
        $this->imageHelper  = $imageHelper;
    }


    public function getProductById($id)
    {
        return $this->productModel->load($id);
    }

    public function getImageUrl($product)
    {
        return $this->imageHelper->init($product, 'product_page_image_small')->setImageFile($product->getImage())->resize(160, 160)->getUrl();
    }
}
