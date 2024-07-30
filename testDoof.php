require_once 'vendor/autoload.php'; // Dipende dalle tue esigenze specifiche
// Configurazione dell'API di Magento
$baseUrl = 'https://www.example.com'; $accessToken = 'your_access_token';
// Configurazione dell'API di Doofinder
$doofinderApiKey = 'your_api_key';
// Recupera i dati del catalogo dei prodotti da Magento
$client = new GuzzleHttp\Client(); $response = $client->request('GET', $baseUrl . '/rest/V1/products', [ 'headers' => [ 'Authorization' => 'Bearer ' . $accessToken ] ]);
$products = json_decode($response->getBody(), true);
