<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product;

use Collections\Exceptions\InvalidArgumentException;
use League\Csv\Exception as CsvException;
use League\Csv\Reader;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataProviderInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Nextouch\ImportExport\Model\Wins\Collection\FeatureSet as FeatureSetCollection;
use Nextouch\ImportExport\Model\Wins\Feature;
use Nextouch\ImportExport\Model\Wins\FeatureSet;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\reduce;

class WinsFeatureSetDataProvider implements EntityDataProviderInterface
{
    public const CSV_FILENAME = 'caratteristiche.csv';
    private const CSV_DELIMITER = ';';
    private const CSV_HEADER = [
        'CodiceProdotto',
        'CodiceEcatDM',
        'CodiceCaratteristica',
        'DescrizioneCaratteristica',
        'CodiceValore',
        'Valore',
    ];

    private IoInterface $client;
    private ImportExportConfig $config;

    public function __construct(IoInterface $client, ImportExportConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchData(): \IteratorAggregate
    {
        $featureSets = new FeatureSetCollection();

        try {
            $this->openConnection();
            $featureSets = $this->fetchFeatureSets();
        } catch (\Exception $e) {
            throw new LocalizedException(__('Failed to read Wins "caratteristiche.csv". Error: %1', $e->getMessage()));
        } finally {
            $this->client->close();
        }

        return $featureSets;
    }

    private function openConnection(): void
    {
        $config = $this->config->getWinsConfig();
        $this->client->open($config);
    }

    /**
     * @throws CsvException
     * @throws InvalidArgumentException
     * @throws LocalizedException
     */
    private function fetchFeatureSets(): FeatureSetCollection
    {
        return reduce(function (FeatureSetCollection $acc, array $record) {
            if (!$this->validateRecord($record)) {
                throw new LocalizedException(__('Invalid feature set record: %1', json_encode($record)));
            }

            $featureSet = $acc->findReference(FeatureSet::fromArray($record));
            $featureSet->addFeature(Feature::fromArray($record));

            return $acc->addIfNotExists($featureSet);
        }, $this->fetchRecords(), new FeatureSetCollection());
    }

    /**
     * @throws CsvException
     */
    private function fetchRecords(): \Iterator
    {
        $filePath = $this->config->getWinsFilePath(self::CSV_FILENAME);
        $content = $this->client->read($filePath);

        if (!is_string($content)) {
            return new \EmptyIterator();
        }

        $csv = Reader::createFromString($content);
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(self::CSV_DELIMITER);

        return $csv->getRecords();
    }

    private function validateRecord(array $record): bool
    {
        return all(fn($column) => array_key_exists($column, $record), self::CSV_HEADER);
    }
}
