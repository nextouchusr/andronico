<?php
declare(strict_types=1);

namespace Nextouch\ProcessDoofinderIndex\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Filesystem\Io\Ftp as FtpClient;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Helper\Image as ImageHelper;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\State;

class ProcessIndexes
{
    protected $collectionFactory;
    protected $ftpClient;
    protected $logger;
    protected $productHelper;
    protected $imageHelper;
    protected $state;

    public function __construct(
        CollectionFactory $collectionFactory,
        FtpClient $ftpClient,
        ProductHelper $productHelper,
        ImageHelper $imageHelper,
        LoggerInterface $logger,
        State $state
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->ftpClient = $ftpClient;
        $this->productHelper = $productHelper;
        $this->imageHelper = $imageHelper;
        $this->logger = $logger;
        $this->state = $state;
    }

    public function execute()
    {
        try {
            $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            // Step 1: Retrieve Samsung products
            $productCollection = $this->collectionFactory->create()
                ->addAttributeToSelect('*');

            // Step 2: Prepare CSV data
            $csvData = [];
            foreach ($productCollection as $product) {

                $this->logger->info('Product SKU: ' . $product);
                if($brand_to_confront=='MIELE')
                $csvData[] = [
                    'availability' => 'In stock', // Example availability
                    'brand' => $product->getAttributeText('brand'),
                    'categories' => implode('|', $product->getCategoryIds()), // Example categories
                    'description' => $product->getDescription(), // Example description
                    'slug' => $product->getUrlKey(), // Example slug
                    'id' => $product->getId(),
                    'image_link' => $this->imageHelper->init($product, 'product_page_image_small')->getUrl(),
                    'link' => $this->productHelper->getProductUrl($product),
                    'mpn' => $product->getSku(), // Example MPN
                    'price' => $product->getPrice(),
                    'title' => $product->getName(),
                ];
            }

            $csvFile = '/var/tmp/miele_products.csv';
            $this->createCsvFile($csvFile, $csvData);

        } catch (\Exception $e) {
            $this->logger->error('Error exporting doofinder products: ' . $e->getMessage());
        }
    }

    private function createCsvFile(string $filePath, array $data)
    {
        if (!empty($data)) { // Verifica se ci sono dati
            $file = fopen($filePath, 'w');
            $header = ['availability', 'brand', 'categories', 'description', 'group_leader', 'group_id', 'slug', 'id', 'image_link', 'link', 'mpn', 'price', 'tags', 'title', 'color', 'size', 'sale_price'];
            fputcsv($file, $header, ';');

            foreach ($data as $row) {
                fputcsv($file, $row,';');
            }
        } else {
            $this->logger->warning('No data to export to CSV.');
        }

        fclose($file);
    }

}

