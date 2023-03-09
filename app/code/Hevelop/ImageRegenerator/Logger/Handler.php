<?php
namespace Hevelop\ImageRegenerator\Logger;

use Monolog\Logger;

/**
 * Class Handler
 * @package Hevelop\DuplicatedImageCleaner\Logger
 */
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/hevelop_imageregenerator.log';
}
