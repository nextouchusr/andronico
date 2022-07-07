<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 25.10.18
 * Time: 10:45
 */

namespace Axepta\Paymentservice\Model\Config\Source;

use Axepta\Paymentservice\Helper\Data;
use Magento\Framework\Option\ArrayInterface;

class PaymentAction implements ArrayInterface
{
    /**
     * @var Data
     */
    private $helper;

    public function __construct(Data $helper)
    {

        $this->helper = $helper;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {

        return [
            ['value' => 'authorize', 'label' => __('Preautorizzazione')],
            ['value' => 'authorize_capture', 'label' => __('Acquisto')],
            ['value' => 'verify', 'label' => __('Verifica')]
        ];
    }
}
