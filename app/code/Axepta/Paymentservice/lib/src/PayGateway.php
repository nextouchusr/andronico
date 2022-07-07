<?php
namespace Axepta\Payment;

use Payment\PaymentUtils;

final class PayGateway
{
    const IGFC = 'igfs';
    const CMPT1 = 'computop';
    const AXEPTA_PG = 'axepta';

    /**
     * Return the possible payment types
     *
     * @param
     * @return array|object
     */
    public static function getPaymentTypes()
    {
        return [self::IGFC=> PaymentUtils::getLabelText('PAYMENT_TYPE_IGFS'),self::CMPT1  => PaymentUtils::getLabelText('PAYMENT_TYPE_CMPT')];
    }

    /**
     * Manage all main integration function
     *
     * @param string $paymenttype
     * @param bool $test
     * @return \Payment\Gateway\Igfs\Gateway|\Payment\Gateway\Computop\Gateway|boolean
     */
    public function get($paymenttype, $test = false)
    {
        return self::getIstance($paymenttype, $test);
    }

    /**
     * Manage all main integration function
     *
     * @param string $paymenttype
     * @param bool $test
     * @return \Payment\Gateway\Igfs\Gateway|\Payment\Gateway\Computop\Gateway|boolean
     */
    public static function getIstance($paymenttype, $test)
    {
        $return =null;
        switch ($paymenttype) {
            case self::IGFC:
                $return = new \Payment\Gateway\Igfs\Gateway($test);
                break;
            case self::CMPT1:
                $return = new \Payment\Gateway\Computop\Gateway($test);
                break;
            case self::AXEPTA_PG:
                $return = new \Payment\Gateway\Axepta\Gateway($test);
                break;
            default:
                break;
        }
        return $return;
    }
}
