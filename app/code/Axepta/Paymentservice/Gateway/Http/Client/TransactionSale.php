<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 18.10.18
 * Time: 16:24
 */

namespace Axepta\Paymentservice\Gateway\Http\Client;

class TransactionSale extends AbstractTransaction
{

    /** Process http request
     * @param array $data
     * @return mixed
     */
    protected function process(array $data)
    {
        return $this->adapterFactory->create()->verify($data);
    }
}
