<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17.10.18
 * Time: 10:55
 */

namespace Axepta\Paymentservice\Model;

use Magento\Payment\Model\Method\Cc;
use Magento\Payment\Model\MethodInterface;

abstract class Axepta implements MethodInterface
{
    const METHOD_CODE = 'axepta_paymentservice';
    const STATE_AUTHORIZED = 'authorized';
    const STATE_COMPLETE = 'complete';
    const STATE_NEW = 'new';
    const STATE_PROCESSING = 'processing';
    const STATE_CANCELED = 'canceled';
    protected $_code = self::METHOD_CODE;


    abstract function init($params = []);

    abstract function verify($params = []);
}
