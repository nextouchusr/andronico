<?php
declare(strict_types=1);

namespace Nextouch\Sidea\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Nextouch\Sidea\Service\Event\SendAbandonedCart as SendAbandonedCartService;
use function Lambdish\Phunctional\each;

class SendAbandonedCarts
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private CartRepositoryInterface $cartRepository;
    private SendAbandonedCartService $sendAbandonedCartService;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CartRepositoryInterface $cartRepository,
        SendAbandonedCartService $sendAbandonedCartService
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->cartRepository = $cartRepository;
        $this->sendAbandonedCartService = $sendAbandonedCartService;
    }

    public function execute(): void
    {
        $yesterday = date('Y-m-d', strtotime('yesterday'));
        $today = date('Y-m-d');

        $criteria = $this->searchCriteriaBuilder
            ->addFilter('is_active', true)
            ->addFilter('items_count', 0, 'gt')
            ->addFilter('customer_id', 'null', 'neq')
            ->addFilter('created_at', $yesterday, 'gteq')
            ->addFilter('created_at', $today, 'lteq')
            ->create();

        $quotes = $this->cartRepository->getList($criteria)->getItems();

        each(fn(Quote $quote) => $this->sendAbandonedCartService->execute($quote), $quotes);
    }
}
