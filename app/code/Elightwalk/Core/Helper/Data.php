<?php

namespace Elightwalk\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Image;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory as WishlistCollection;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\ObjectManagerInterface;

class Data extends AbstractHelper
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
     * @var WishlistCollection
     */
    protected $wishlistCollection;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * __construct
     *
     * @param Context $context
     * @param Product $productModel
     * @param Image $imageHelper
     * @param WishlistCollection $wishlistCollection
     * @param CustomerSession $customerSession
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        Context $context,
        Product $productModel,
        Image $imageHelper,
        WishlistCollection $wishlistCollection,
        CustomerSession $customerSession,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($context);
        $this->productModel       = $productModel;
        $this->imageHelper        = $imageHelper;
        $this->wishlistCollection = $wishlistCollection;
        $this->customerSession    = $customerSession;
        $this->objectManager      = $objectManager;
    }

    /**
     * GetProductById
     *
     * @param int $id
     * @return object|null
     */
    public function getProductById($id)
    {
        return $this->productModel->load($id);
    }

    /**
     * GetImageUrl
     *
     * @param object $product
     * @return string|object|null
     */
    public function getImageUrl($product)
    {
        return $this->imageHelper->init($product, 'product_page_image_small')->setImageFile($product->getImage())->resize(160, 160)->getUrl();
    }

    /**
     * GetWishlistCollection
     *
     * @return WishlistCollection
     */
    public function getWishlistCollection()
    {
        return $this->wishlistCollection->create();
    }

    /**
     * GetCustomerFromSession
     *
     * @return CustomerSession
     */
    public function getCustomerFromSession()
    {
        if ($this->customerSession == null || $this->customerSession->getCustomerId() == null) {
            $this->customerSession = $this->objectManager->create(CustomerSession::class);
        }

        return $this->customerSession;
    }

    /**
     * GetCustomerWishlist
     *
     * @param int $productId
     * @param boolean $getItemsCount
     * @return mixed
     */
    public function getCustomerWishlist($productId, $getItemsCount = false)
    {
        try {
            $collection = $this->getWishlistCollection();
            $collection->addCustomerIdFilter($this->getCustomerFromSession()->getCustomerId());
            $collection->addFieldToFilter('product_id', $productId);

            if ($getItemsCount) {
                return $collection->getSize();
            }
        } catch (\Exception $exception) {
            // Do Nothing.
        } catch (\Error $error) {
            // Do Nothing.
        }

        return $collection;
    }
}
