<?php
declare(strict_types=1);

namespace Nextouch\Gls\Plugin\Model;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Sales\Api\Data\ShipmentCommentCreationInterface;
use Magento\Sales\Api\Data\ShipmentCreationArgumentsInterface;
use Magento\Sales\Api\Data\ShipmentTrackCreationInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\ShipOrderInterface;
use Magento\User\Api\Data\UserInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;
use Nextouch\Gls\Model\Carrier\Gls;
use Nextouch\Gls\Service\SendTrackingLink;
use function Lambdish\Phunctional\first;

class SendGlsTrackingLink
{
    private UserContextInterface $userContext;
    private CollectionFactory $collectionFactory;
    private OrderRepositoryInterface $orderRepository;
    private SendTrackingLink $sendTrackingLinkService;

    public function __construct(
        UserContextInterface $userContext,
        CollectionFactory $collectionFactory,
        OrderRepositoryInterface $orderRepository,
        SendTrackingLink $sendTrackingLinkService
    ) {
        $this->userContext = $userContext;
        $this->collectionFactory = $collectionFactory;
        $this->orderRepository = $orderRepository;
        $this->sendTrackingLinkService = $sendTrackingLinkService;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterExecute(
        ShipOrderInterface $subject,
        string $result,
        $orderId,
        array $items,
        bool $notify = false,
        bool $appendComment = false,
        ShipmentCommentCreationInterface $comment = null,
        array $tracks = [],
        array $packages = [],
        ShipmentCreationArgumentsInterface $arguments = null
    ): string {
        if ($this->isGlsUser() && $tracks) {
            /** @var ShipmentTrackCreationInterface $track */
            $track = first($tracks);

            $order = $this->orderRepository->get($orderId);
            $this->sendTrackingLinkService->execute($order, $track);
        }

        return $result;
    }

    private function isGlsUser(): bool
    {
        return (
            $this->userContext->getUserType() === UserContextInterface::USER_TYPE_ADMIN &&
            $this->getUser()->getUserName() === Gls::ADMIN_USERNAME
        );
    }

    private function getUser(): UserInterface
    {
        $userId = $this->userContext->getUserId();

        /** @var UserInterface $user */
        $user = $this->collectionFactory->create()->getItemById($userId);

        return $user;
    }
}
