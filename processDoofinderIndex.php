
<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');

try {
    // Esegui il cron job
    $exportProducts = $objectManager->create('Nextouch\ProcessDoofinderIndex\Cron\ProcessIndexes');
    $exportProducts->execute();

    // Output di un messaggio se il job va a buon fine
    echo "Processamento indici  eseguito con successo.\n";

    // Log del messaggio se il job va a buon fine
    $objectManager->get('\Psr\Log\LoggerInterface')->info('Cron job eseguito con successo.');
} catch (\Exception $e) {
    // Log dell'eccezione se il job fallisce
    $objectManager->get('\Psr\Log\LoggerInterface')->error('Errore durante l\'esecuzione del cron job: ' . $e->getMessage());

    // Output dell'eccezione se il job fallisce
    echo "Errore durante l'esecuzione del cron job doofinder export: " . $e->getMessage() . "\n";
}


