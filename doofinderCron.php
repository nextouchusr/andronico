<?php
use Magento\Framework\App\Bootstrap;
use Psr\Log\LoggerInterface;

require __DIR__ . '/app/bootstrap.php';

try {
    $bootstrap = Bootstrap::create(BP, $_SERVER);
    $objectManager = $bootstrap->getObjectManager();
    $state = $objectManager->get('Magento\Framework\App\State');
    $state->setAreaCode('frontend');

    // Recupera il logger
    $logger = $objectManager->get(LoggerInterface::class);

    // Funzione per inviare una richiesta DELETE
    function deleteIndex($apiUrl, $apiKey, $logger) {
        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $logger->error('Errore cURL DELETE: ' . curl_error($ch));
        } else {
            $logger->info('Doofinder DELETE API response: ' . $response);
        }

        curl_close($ch);
    }

    // Funzione per inviare una richiesta CREATE
    function createIndex($apiUrl, $apiKey, $logger) {
        $data = [
            'name' => 'product',
            'preset' => 'product',
            'options' => [
                'exclude_out_of_stock_items' => false
            ],
            'datasources' => [
                [
                    'type' => 'file',
                    'options' => [
                        'feed_type' => 'json',
                        'url' => 'http://andronico_index_url(notUsed).com/data.json'
                    ]
                ]
            ]
        ];

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Token ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $logger->error('Errore cURL CREATE: ' . curl_error($ch));
        } else {
            $logger->info('Doofinder CREATE API response: ' . $response);
        }

        curl_close($ch);
    }

    // Funzione per inviare un batch di prodotti a Doofinder
    function sendToDoofinder($productData, $apiUrl, $apiKey, $logger) {
        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Token ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($productData));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $logger->error('Errore cURL: ' . curl_error($ch));
        } else {
            $logger->info('Doofinder API response: ' . $response);
        }

        curl_close($ch);
    }

    // API Keys and URLs
    $searchZone = 'eu1';
    $hashId = 'aa6b463a178d87a31c7feb99d42e47ef';
    $apiKey = 'eu1-1cd24e82becbd3894c89bc3aac26f1422f8c90fb';
    $deleteApiUrl = "https://eu1-api.doofinder.com/api/v2/search_engines/aa6b463a178d87a31c7feb99d42e47ef/indices/product";
    $createApiUrl = "https://eu1-api.doofinder.com/api/v2/search_engines/aa6b463a178d87a31c7feb99d42e47ef/indices";
    $bulkApiUrl = "https://eu1-api.doofinder.com/api/v2/search_engines/aa6b463a178d87a31c7feb99d42e47ef/indices/product/items/_bulk";

    // Step 1: Delete the existing index
    deleteIndex($deleteApiUrl, $apiKey, $logger);

    // Step 2: Create a new index
    createIndex($createApiUrl, $apiKey, $logger);

    // Recupera il repository delle categorie
    $categoryRepository = $objectManager->get('Magento\Catalog\Model\CategoryRepository');
    $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

    $pageSize = 100;
    $currentPage = 1;

    do {
        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $products = $productCollection->create()
            ->addAttributeToSelect('*') // Seleziona tutti gli attributi del prodotto
            ->addFieldToFilter('status', ['eq' => \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED]) // Solo prodotti abilitati
            ->joinField(
                'qty',
                'cataloginventory_stock_item',
                'qty',
                'product_id=entity_id',
                null,
                'left'
            )
            ->setPageSize($pageSize) // Limita il numero di prodotti per pagina
            ->setCurPage($currentPage)
            ->load();

        $productData = [];

        foreach ($products as $product) {
            $categoryNames = [];
            $categoryIds = $product->getCategoryIds();

            foreach ($categoryIds as $categoryId) {
                try {
                    $category = $categoryRepository->get($categoryId);
                    $categoryNames[] = $category->getName();
                } catch (\Exception $e) {
                    $logger->error('Errore nel recuperare il nome della categoria: ' . $e->getMessage());
                }
            }

            // Recupera l'URL dell'immagine
            $baseUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $imageUrl = $baseUrl . 'catalog/product' . $product->getImage();

            $inStock = ($product->getQty() > 0) ? "in stock" : "out of stock";

            // Recupera il brand del prodotto
            $brand = $product->getAttributeText('brand');

            $productEntry = [
                'title' => $product->getName(),
                'link' => $product->getProductUrl(),
                'image_link' => $imageUrl, // Recupera il link dell'immagine dalla cartella /media/catalog
                'id' => $product->getId(),
                'color' => $product->getAttributeText('color'),
                'price' => $product->getPrice(),
                'sale_price' => $product->getSpecialPrice(),
                'categories' => $categoryNames, // Sostituisce gli ID delle categorie con i nomi
                'availability' => $inStock, // Aggiunge l'informazione sulla disponibilità
                'brand' => $brand, // Aggiunge la proprietà brand
            ];
            $productData[] = $productEntry;
        }

        if (!empty($productData)) {
            // Invia il batch di prodotti a Doofinder
            sendToDoofinder($productData, $bulkApiUrl, $apiKey, $logger);
        }

        $currentPage++;
    } while ($products->count() == $pageSize);

} catch (\Exception $e) {
    // Log dell'eccezione
    $logger->error('Errore nello script: ' . $e->getMessage());
}
?>

