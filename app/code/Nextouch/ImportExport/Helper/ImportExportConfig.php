<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class ImportExportConfig extends AbstractHelper
{
    private const XML_PATH_WINS_HOST = 'import_export/wins/host';
    private const XML_PATH_WINS_USERNAME = 'import_export/wins/username';
    private const XML_PATH_WINS_PASSWORD = 'import_export/wins/password';

    public function getWinsConfig(): array
    {
        return [
            'host' => $this->getWinsHost(),
            'username' => $this->getWinsUsername(),
            'password' => $this->getWinsPassword(),
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
}
