<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Mail;

use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Nextouch\Findomestic\Helper\FindomesticConfig;
use Nextouch\Rma\Api\Data\RmaInterface;

class RefundApproved
{
    private RmaInterface $return;

    private TransportBuilder $transportBuilder;
    private StateInterface $inlineTranslation;
    private StoreManagerInterface $storeManager;
    private FindomesticConfig $config;

    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        FindomesticConfig $config
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->config = $config;
    }

    public function getReturn(): RmaInterface
    {
        return $this->return;
    }

    public function setReturn(RmaInterface $return): self
    {
        $this->return = $return;

        return $this;
    }

    /**
     * @throws LocalizedException
     */
    public function send(): void
    {
        try {
            $this->inlineTranslation->suspend();

            $this->transportBuilder
                ->setTemplateIdentifier($this->config->getRefundApprovedSalesEmail())
                ->setTemplateOptions([
                    'area' => Area::AREA_ADMINHTML,
                    'store' => $this->storeManager->getStore()->getId(),
                ])
                ->setTemplateVars([
                    'return' => $this->getReturn(),
                ])
                ->setFromByScope([
                    'name' => $this->config->getSenderEmail(),
                    'email' => $this->config->getSenderEmail(),
                ])
                ->addTo($this->config->getRecipientEmail());

            $this->transportBuilder->getTransport()->sendMessage();
        } catch (LocalizedException $e) {
            throw new LocalizedException(__($e->getMessage()));
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}
