<?php
use Magento\Framework\App\Bootstrap;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Helper\Image as ImageHelper;

require __DIR__ . '/app/bootstrap.php';

try {
    $bootstrap = Bootstrap::create(BP, $_SERVER);
    $objectManager = $bootstrap->getObjectManager();
    $state = $objectManager->get('Magento\Framework\App\State');
    $state->setAreaCode('frontend');

    // Recupera il logger
    $logger = $objectManager->get(LoggerInterface::class);

    // Funzione per generare e salvare il file CSV
    function generateAndSaveCSV($products, $filePath) {
        $file = fopen($filePath, 'w');
        if ($file === false) {
            throw new Exception('Impossibile creare il file CSV');
        }

        // Intestazioni del CSV
        fputcsv($file, ['entity_id', 'name', 'description', 'category', 'price', 'url', 'image_link']);

        foreach ($products as $product) {
            $categoryNames = implode(', ', $product['categories']);
            $row = [
                $product['id'],
                $product['title'],
                $product['description'], // Assicurati che il campo 'description' sia correttamente recuperato
                $categoryNames,
                $product['price'],
                $product['link'],
                $product['image_link'],
            ];
            fputcsv($file, $row);
        }

        fclose($file);
    }

    // Recupera il repository delle categorie
    $categoryRepository = $objectManager->get('Magento\Catalog\Model\CategoryRepository');
    $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

    // Recupera tutti i prodotti
    $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
    $products = $productCollection->create()
        ->addAttributeToSelect('*') // Seleziona tutti gli attributi del prodotto
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

        $productEntry = [
            'id' => $product->getId(),
            'title' => $product->getName(),
            'description' => $product->getDescription(), // Assicurati che il campo 'description' sia correttamente recuperato
            'categories' => $categoryNames,
            'price' => $product->getPrice(),
            'link' => $product->getProductUrl(),
            'image_link' => $imageUrl,
        ];

        $productData[] = $productEntry;
    }

    // Genera e salva il file CSV
    $csvFileName = 'products.txt';
    $csvFilePath = BP . '/pub/media/' . $csvFileName;
    generateAndSaveCSV($productData, $csvFilePath);

    $logger->info('File CSV creato con successo: ' . $csvFilePath);

} catch (\Exception $e) {
    // Log dell'eccezione
    $logger->error('Errore nello script: ' . $e->getMessage());
}
?>

