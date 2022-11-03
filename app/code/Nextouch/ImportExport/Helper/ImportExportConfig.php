<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class ImportExportConfig extends AbstractHelper
{
    private const XML_PATH_WINS_HOST = 'import_export/wins/host';
    private const XML_PATH_WINS_USERNAME = 'import_export/wins/username';
    private const XML_PATH_WINS_PASSWORD = 'import_export/wins/password';
    private const XML_PATH_WINS_LOCATION = 'import_export/wins/location';
    private const XML_PATH_WINS_PASSIVE_MODE = 'import_export/wins/passive_mode';

    public function getWinsConfig(): array
    {
        return [
            'host' => $this->getWinsHost(),
            'user' => $this->getWinsUsername(),
            'password' => $this->getWinsPassword(),
            'passive' => $this->isWinsPassiveModeEnabled(),
        ];
    }

    private function getWinsHost(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_WINS_HOST);
    }

    private function getWinsUsername(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_WINS_USERNAME);
    }

    private function getWinsPassword(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_WINS_PASSWORD);
    }

    public function getWinsFilePath(string $filename): string
    {
        $location = $this->getWinsLocation();

        return "$location/$filename";
    }

    public function getWinsProcessedFilePath(string $filename): string
    {
        $location = $this->getWinsLocation();
        $now = date('YmdHis');

        return "$location/processed/{$now}_$filename";
    }

    public function getWinsUnprocessedFilePath(string $filename): string
    {
        $location = $this->getWinsLocation();
        $now = date('YmdHis');

        return "$location/unprocessed/{$now}_$filename";
    }

    public function getWinsExtractCommandFilePath(): string
    {
        $location = $this->getWinsLocation();

        return "$location/extract_command.sh";
    }

    public function getWinsLocation(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_WINS_LOCATION);
    }

    public function isWinsPassiveModeEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_WINS_PASSIVE_MODE);
    }
}
