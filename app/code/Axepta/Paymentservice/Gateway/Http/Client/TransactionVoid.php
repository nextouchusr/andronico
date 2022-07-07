<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 24.10.18
 * Time: 11:06
 */

namespace Axepta\Paymentservice\Gateway\Http\Client;

class TransactionVoid extends AbstractTransaction
{

    /** Process http request
     * @param array $data
     * @return mixed
     */
    protected function process(array $data)
    {
        return $this->adapterFactory->create()->void($data);
    }
}
