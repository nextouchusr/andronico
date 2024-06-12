<?php

declare(strict_types=1);

namespace Elightwalk\Core\Model;

use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Helper\Image;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class GenerateMieleProductCsv
{
    const MIELE_BRAND_ID = 25641;
    const CSV_FILE_PATH = 'export/miele/products.csv';

    /**
     * $_logger
     *
     * @var Psr\Log\LoggerInterface
     */
    private $_logger;

    /**
     * @var CollectionFactory
     */
    private $_collectionFactory;

    /**
     * @var Image
     */
    private $_imageHelper;

    private $directory;

    /**
     * __construct function
     *
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     * @param CollectionFactory $collectionFactory
     * @param Image $imageHelper
     */
    public function __construct(
        Filesystem $filesystem,
        LoggerInterface $logger,
        CollectionFactory $collectionFactory,
        Image $imageHelper
    ) {
        $this->_logger            = $logger;
        $this->_collectionFactory = $collectionFactory;
        $this->_imageHelper       = $imageHelper;
        $this->directory          = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    public function execute()
    {
        try {
            $collection = $this->_collectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToFilter('brand', ['eq' => self::MIELE_BRAND_ID]);
            if (count($collection)) {

                $file    = self::CSV_FILE_PATH;
                $headers = ['NAME', 'PRICE', 'OFFERURL', 'PICTURE', 'EAN'];
                $this->directory->create('export');
                $stream = $this->directory->openFile($file, 'w+');
                $stream->lock();
                $stream->writeCsv($headers, ';');

                foreach ($collection as $product) {
                    $data             = [];
                    $data['NAME']     = $product->getName();
                    $data['PRICE']    = $product->getPrice();
                    $data['OFFERURL'] = $product->getProductUrl();
                    $data['PICTURE']  = $this->_imageHelper->init($product, 'product_page_image')->setImageFile($product->getImage())->getUrl();
                    $data['EAN']      = $product->getEan();
                    $stream->writeCsv($data, ';');
                }

                $stream->unlock();
                $stream->close();

                print_r('csv generated at ' . $file);  echo "\n";

            }
        } catch (LocalizedException $e) {
            print_r($e->getMessage());
            $this->_logger->debug($e->getMessage());
            echo "\n";
        }
    }
}
