<?php
declare(strict_types=1);

namespace Nextouch\MieleTransferModule\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Filesystem\Io\Ftp as FtpClient;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Helper\Image as ImageHelper;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\State;

class ExportMieleProducts
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
            // Step 1: Retrieve Miele products
            $productCollection = $this->collectionFactory->create()
                ->addAttributeToSelect('*');

            // Step 2: Prepare CSV data and EAN list
            $csvData = [];
            $eanList = [];
            foreach ($productCollection as $product) {
                if ($product->getAttributeText('brand')) {
                    $brand_to_confront = strtoupper($product->getAttributeText('brand'));
                    $this->logger->info('Product SKU: ' . $product->getSku() . ', Brand: ' . $brand_to_confront);
                    if ($brand_to_confront == 'MIELE') {
                        $csvData[] = [
                            'name' => $product->getName(),
                            'price' => $product->getPrice(),
                            'brand' => $product->getAttributeText('brand'),
                            'url' => $this->productHelper->getProductUrl($product),
                            'image' => $this->imageHelper->init($product, 'product_page_image_small')->getUrl(),
                            'ean' => $product->getEan()
                        ];

                        // Gestione di più EAN separati da virgole
                        $eanCodes = explode(',', $product->getEan());
                        foreach ($eanCodes as $ean) {
                            $eanList[] = trim($ean);
                        }
                    }
                }
            }

            // Step 3: Create CSV file
            $csvFile = '/var/tmp/miele_products.csv';
            $this->createCsvFile($csvFile, $csvData);

            // Step 4: Upload CSV to FTP
            $ftpConfig = [
                'host' => 'files.zoovu.com',
                'user' => 'logistica@nextouch.it',
                'password' => 'RA@@pRw2#PbgjqAA'
            ];
            $this->ftpClient->open($ftpConfig);
            if (!empty($csvData)) {
                $this->ftpClient->write('/miele.csv', file_get_contents($csvFile));
            }
            $this->ftpClient->close();

            // Step 5: Update EAN list file
            $eanFile = BP . '/pub/media/miele/ean_list.txt';
            $this->updateEanFile($eanFile, $eanList);

            $this->logger->info('Miele products exported and uploaded to FTP successfully.');
        } catch (\Exception $e) {
            $this->logger->error('Error exporting Miele products: ' . $e->getMessage());
        }
    }

    private function createCsvFile(string $filePath, array $data)
    {
        if (!empty($data)) { // Verifica se ci sono dati
            $file = fopen($filePath, 'w');
            $header = ['name', 'price', 'brand', 'url', 'image', 'ean'];
            fputcsv($file, $header, ';');

            foreach ($data as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        } else {
            $this->logger->warning('No data to export to CSV.');
        }
    }

    private function updateEanFile(string $filePath, array $eanList)
    {
        $file = fopen($filePath, 'w');
        foreach ($eanList as $ean) {
            fwrite($file, $ean . PHP_EOL);
        }
        fclose($file);
    }
}

