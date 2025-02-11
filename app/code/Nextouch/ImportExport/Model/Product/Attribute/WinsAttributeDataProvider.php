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
use Nextouch\ImportExport\Model\Wins\Collection\Template as TemplateCollection;
use Nextouch\ImportExport\Model\Wins\Group;
use Nextouch\ImportExport\Model\Wins\Property;
use Nextouch\ImportExport\Model\Wins\Property\SelectableProperty;
use Nextouch\ImportExport\Model\Wins\PropertyValue;
use Nextouch\ImportExport\Model\Wins\Template;
use function Lambdish\Phunctional\all;
use function Lambdish\Phunctional\reduce;

class WinsAttributeDataProvider implements EntityDataProviderInterface
{
    public const CSV_FILENAME = 'template.csv';
    private const CSV_DELIMITER = ';';
    private const CSV_HEADER = [
        'CodiceTemplate',
        'DescrizioneTemplate',
        'CodiceGruppo',
        'DescrizioneGruppo',
        'CodiceCaratteristica',
        'DescrizioneCaratteristica',
        'TipoCaratteristica',
        'Ordinamento',
        'CodiceValore',
        'DescrizioneValore',
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
        $templates = new TemplateCollection();

        try {
            $this->openConnection();
            $templates = $this->fetchTemplates();
        } catch (\Exception $e) {
            throw new LocalizedException(__('Failed to read Wins "template.csv". Error: %1', $e->getMessage()));
        } finally {
            $this->client->close();
        }

        return $templates;
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
    private function fetchTemplates(): TemplateCollection
    {
        return reduce(function (TemplateCollection $acc, array $record) {
            $record += ['CodiceGruppo' => 'attributes', 'DescrizioneGruppo' => 'Attributes'];

            if (!$this->validateRecord($record)) {
                throw new LocalizedException(__('Invalid template record: %1', json_encode($record)));
            }

            try {
                $template = $acc->findReference(Template::fromArray($record));
                $group = $template->findGroupReference(Group::fromArray($record));
                $property = $group->findPropertyReference(Property::fromArray($record));

                if ($property instanceof SelectableProperty) {
                    $value = $property->findValueReference(PropertyValue::fromArray($record));
                    $property->addValueIfNotExists($value);
                }

                $group->addPropertyIfNotExists($property);
                $template->addGroupIfNotExists($group);

                return $acc->addIfNotExists($template);
            } catch (LocalizedException $e) {
                return $acc;
            }
        }, $this->fetchRecords(), new TemplateCollection());
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
