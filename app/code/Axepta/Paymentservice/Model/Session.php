<?php
/**
 * Copyright © Axepta Spa All rights reserved.
 * See LICENSE for license details.
 */
namespace Axepta\Paymentservice\Model;

use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{

     /**
      * Add order IncrementId to session
      *
      * @param string $orderIncrementId
      * @return void
      */
    public function addCheckoutOrderIncrementId($orderIncrementId)
    {
        $orderIncIds = $this->getOrderIncrementIds();
        if (!$orderIncIds) {
            $orderIncIds = [];
        }
        $orderIncIds[$orderIncrementId] = 1;
        $this->setOrderIncrementIds($orderIncIds);
    }

    /**
     * Remove order IncrementId from session
     *
     * @param string $orderIncrementId
     * @return void
     */
    public function removeCheckoutOrderIncrementId($orderIncrementId)
    {
        $orderIncIds = $this->getOrderIncrementIds();

        if (!is_array($orderIncIds)) {
            return;
        }

        if (isset($orderIncIds[$orderIncrementId])) {
            unset($orderIncIds[$orderIncrementId]);
        }
        $this->setOrderIncrementIds($orderIncIds);
    }

    /**
     * Return if order incrementId is in session.
     *
     * @param string $orderIncrementId
     * @return bool
     */
    public function isCheckoutOrderIncrementIdExist($orderIncrementId)
    {
        $orderIncIds = $this->getOrderIncrementIds();
        if (is_array($orderIncIds) && isset($orderIncIds[$orderIncrementId])) {
            return true;
        }
        return false;
    }

    /**
     * Set quote id to session
     *
     * @param int|string $id
     * @return $this
     */
    public function setQuoteId($id)
    {
        $this->storage->setQuoteId($id);
        return $this;
    }
}
