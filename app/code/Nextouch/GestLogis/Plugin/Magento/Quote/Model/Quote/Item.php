<?php

namespace Nextouch\GestLogis\Plugin\Magento\Quote\Model\Quote;

use Magento\Quote\Model\Quote\Item as QuoteItem;

class Item
{
    /**
     * @param QuoteItem $subject
     * @param $result
     * @param $product
     * @return bool
     */
    public function afterRepresentProduct(
        QuoteItem $subject,
        $result,
        $product
    ) {
        return false;
    }

    /**
     * @param QuoteItem $subject
     * @param $result
     * @param $item
     * @return bool
     */
    public function afterCompare(
        QuoteItem $subject,
        $result,
        $item
    ) {
        return false;
    }

}
