<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 23.10.18
 * Time: 16:22
 */

namespace Axepta\Paymentservice\Gateway\Http\Client;

class TransactionSubmitForSettlement extends AbstractTransaction
{

    /** Process http request
     * @param array $data
     * @return mixed
     */
    protected function process(array $data)
    {
        return $this->adapterFactory->create()->submitForSettlement($data);
    }
}
