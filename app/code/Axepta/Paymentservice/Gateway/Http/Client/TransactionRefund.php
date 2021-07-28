<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 25.10.18
 * Time: 13:43
 */

namespace Axepta\Paymentservice\Gateway\Http\Client;

class TransactionRefund extends AbstractTransaction
{

    /** Process http request
     * @param array $data
     * @return mixed
     */
    protected function process(array $data)
    {
        return $this->adapterFactory->create()->refund($data);
    }
}
