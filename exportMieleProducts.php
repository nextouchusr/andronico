<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');

try {
    // Esegui il cron job
    $exportProducts = $objectManager->create('Nextouch\MieleTransferModule\Cron\ExportMieleProducts');
    $exportProducts->execute();

    // Output di un messaggio se il job va a buon fine
    echo "Export prodotti miele eseguito con successo.\n";

    // Log del messaggio se il job va a buon fine
    $objectManager->get('\Psr\Log\LoggerInterface')->info('Cron job eseguito con successo.');
} catch (\Exception $e) {
    // Log dell'eccezione se il job fallisce
    $objectManager->get('\Psr\Log\LoggerInterface')->error('Errore durante l\'esecuzione del cron job: ' . $e->getMessage());

    // Output dell'eccezione se il job fallisce
    echo "Errore durante l'esecuzione del cron job miele export: " . $e->getMessage() . "\n";
}

