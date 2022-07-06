<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Order;

use Amasty\Orderattr\Model\Entity\EntityData;
use Amasty\Orderattr\Model\Entity\EntityResolver;
use Amasty\Orderattr\Model\Entity\Handler\Save;
use Amasty\Orderattr\Model\Value\Metadata\Form;
use Amasty\Orderattr\Model\Value\Metadata\FormFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Sales\Api\OrderRepositoryInterface;
use Nextouch\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use function Lambdish\Phunctional\first;

class AttachInvoiceToOrder
{
    private EntityResolver $entityResolver;
    private FormFactory $metadataFormFactory;
    private OrderRepositoryInterface $orderRepository;
    private Save $saveHandler;
    private Filesystem $filesystem;
    private LoggerInterface $logger;

    public function __construct(
        EntityResolver $entityResolver,
        FormFactory $metadataFormFactory,
        OrderRepositoryInterface $orderRepository,
        Save $saveHandler,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->entityResolver = $entityResolver;
        $this->metadataFormFactory = $metadataFormFactory;
        $this->orderRepository = $orderRepository;
        $this->saveHandler = $saveHandler;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function execute(array $invoice): bool
    {
        try {
            $this->logger->info(__('Starting to attach Wins invoice %1 to order', $invoice['filename']));

            $this->moveInvoiceToMedia($invoice['filename'], $invoice['content']);

            $invoicePdfFile = sprintf('/invoices/%s', $invoice['filename']);
            $data = [OrderInterface::INVOICE_PDF_FILE => $invoicePdfFile];

            $orderId = (int) first(explode('-', $invoice['filename']));
            $order = $this->orderRepository->get($orderId);
            $entity = $this->entityResolver->getEntityByOrderId($orderId);

            $form = $this->createEntityForm($entity, (int) $order->getStoreId(), (int) $order->getCustomerGroupId());
            $entity->setCustomAttributes([]);
            $form->restoreData($data);

            $this->saveHandler->execute($entity);

            return true;
        } catch (LocalizedException $e) {
            $message = __('Failed to attach invoice %1 to order: Error: %2', $invoice['filename'], $e->getMessage());
            $this->logger->error($message);
        } finally {
            $this->logger->info(__('Finishing to attach Wins invoice %1 to order', $invoice['filename']));
        }

        return false;
    }

    /**
     * @throws FileSystemException
     */
    private function moveInvoiceToMedia(string $filename, string $invoice): void
    {
        $path = "amasty_checkout/invoices/$filename";

        $this->filesystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->writeFile($path, $invoice);
    }

    private function createEntityForm(EntityData $entity, int $store, int $customerGroupId): Form
    {
        $formProcessor = $this->metadataFormFactory->create();
        $formProcessor->setFormCode('adminhtml_checkout')
            ->setEntity($entity)
            ->setStore($store)
            ->setCustomerGroupId($customerGroupId);

        return $formProcessor;
    }
}
