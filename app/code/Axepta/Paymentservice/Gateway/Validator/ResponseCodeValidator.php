<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */

namespace Axepta\Paymentservice\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Axepta\PaymentserviceGateway\Http\Client\ClientMock;

class ResponseCodeValidator extends AbstractValidator
{
    const RESULT_CODE = 'RESULT_CODE';

    /**
     * Performs validation of result code
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        if (!isset($validationSubject['response']) || !is_array($validationSubject['response'])) {
            throw new \InvalidArgumentException('Response does not exist');
        }

        $response = $validationSubject['response'];

        if ($response['error'] === false) {
            return $this->createResult(
                true,
                []
            );
        } else {
            return $this->createResult(
                false,
                [
                    __($response['message']),
                ]
            );
        }
    }
}
