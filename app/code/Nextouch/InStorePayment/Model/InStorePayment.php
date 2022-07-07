<?php
declare(strict_types=1);

namespace Nextouch\InStorePayment\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class InStorePayment extends AbstractMethod
{
    public const METHOD_CODE = 'instore_payment';

    protected $_code = self::METHOD_CODE;
    protected $_isOffline = true;
    protected $_canOrder = true;
    protected $_canRefund = true;
    protected $_canVoid = true;
}
