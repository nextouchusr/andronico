<?php
declare(strict_types=1);

namespace Nextouch\Core\Service\Filesystem\Io;

class Ftp extends \Magento\Framework\Filesystem\Io\Ftp implements IoInterface
{
    public function exec(string $command): bool
    {
        return @ftp_exec($this->_conn, $command);
    }
}
