<?php
declare(strict_types=1);

namespace Nextouch\Customer\Block;

class Agreements extends \Magento\CheckoutAgreements\Block\Agreements
{
    public function getAgreements()
    {
        if (!$this->hasAgreements()) {
            $agreements = $this->_agreementCollectionFactory->create();
            $agreements->addStoreFilter($this->_storeManager->getStore()->getId());
            $agreements->addFieldToFilter('is_active', 1);
            $this->setAgreements($agreements);
        }

        return $this->getData('agreements');
    }
}
