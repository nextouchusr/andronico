<?php
declare(strict_types=1);

namespace Nextouch\Core\Service\Filesystem\Io;

/**
 * @api
 */
interface IoInterface extends \Magento\Framework\Filesystem\Io\IoInterface
{
    public function exec(string $command): bool;
}
