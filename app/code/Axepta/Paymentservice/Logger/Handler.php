<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Logger;

use Axepta\Paymentservice\Model\Ui\ConfigProvider;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;

use Monolog\Logger;

class Handler extends Base
{
    protected $loggerType = Logger::INFO;

    protected $date;

    protected $fileName;

    public function __construct(DriverInterface $filesystem, $filePath = null, $fileName = null)
    {
        $this->date = new \DateTime('now');
        $this->fileName = sprintf('/var/log/%s-%s.log', ConfigProvider::CODE, $this->date->format('Y_m_d'));
        parent::__construct($filesystem, $filePath, $fileName);
    }
}
