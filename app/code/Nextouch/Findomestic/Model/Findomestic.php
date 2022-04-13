<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class Findomestic extends AbstractMethod
{
    public const METHOD_CODE = 'findomestic_paymentservice';

    protected $_code = self::METHOD_CODE;
    protected $_isGateway = true;
    protected $_canOrder = true;
    protected $_canAuthorize = true;
    protected $_canRefund = true;
    protected $_canVoid = true;
}
