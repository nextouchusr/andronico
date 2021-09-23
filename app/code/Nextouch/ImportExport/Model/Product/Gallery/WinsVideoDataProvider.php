<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product\Gallery;

use Collections\Exceptions\InvalidArgumentException;
use League\Csv\Exception as CsvException;
use League\Csv\Reader;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\IoInterface;
use Nextouch\ImportExport\Api\EntityDataProviderInterface;
use Nextouch\ImportExport\Helper\ImportExportConfig;
use Nextouch\ImportExport\Model\Wins\Collection\Video as VideoCollection;
use Nextouch\ImportExport\Model\Wins\Video;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\reduce;

class WinsVideoDataProvider implements EntityDataProviderInterface
{
    public const CSV_FILENAME = 'video.csv';
    private const CSV_DELIMITER = ';';
    private const CSV_HEADER = ['sku', 'titolo', 'url'];

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
        $videos = new VideoCollection();

        try {
            $this->openConnection();
            $videos = $this->fetchVideos();
        } catch (\Exception $e) {
            throw new LocalizedException(__('Failed to read Wins "video.csv". Error: %1', $e->getMessage()));
        } finally {
            $this->client->close();
        }

        return $videos;
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
    private function fetchVideos(): VideoCollection
    {
        return reduce(function (VideoCollection $acc, array $record) {
            if (!$this->validateRecord($record)) {
                throw new LocalizedException(__('Invalid video record: %1', json_encode($record)));
            }

            $video = Video::fromArray($record);

            return $acc->add($video);
        }, $this->fetchRecords(), new VideoCollection());
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
