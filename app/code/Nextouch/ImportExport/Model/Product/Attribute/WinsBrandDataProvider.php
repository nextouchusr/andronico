<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product\Attribute;

use Collections\Exceptions\InvalidArgumentException;
use League\Csv\Exception as CsvException;
use League\Csv\Reader;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataProviderInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Nextouch\ImportExport\Model\Wins\Brand;
use Nextouch\ImportExport\Model\Wins\Collection\Brand as BrandCollection;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\reduce;

class WinsBrandDataProvider implements EntityDataProviderInterface
{
    public const CSV_FILENAME = 'marchi.csv';
    private const CSV_DELIMITER = ';';
    private const CSV_HEADER = [
        'codiceMarchio',
        'descrizioneMarchio',
        'brandOnline',
        'immagine',
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
        $brands = new BrandCollection();

        try {
            $this->openConnection();
            $brands = $this->fetchBrands();
        } catch (\Exception $e) {
            throw new LocalizedException(__('Failed to read Wins "marchi.csv". Error: %1', $e->getMessage()));
        } finally {
            $this->client->close();
        }

        return $brands;
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
    private function fetchBrands(): BrandCollection
    {
        return reduce(function (BrandCollection $acc, array $record) {
            if (!$this->validateRecord($record)) {
                throw new LocalizedException(__('Invalid brand record: %1', json_encode($record)));
            }

            $brand = Brand::fromArray($record);

            return $acc->add($brand);
        }, $this->fetchRecords(), new BrandCollection());
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
