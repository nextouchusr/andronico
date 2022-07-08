<?php
declare(strict_types=1);

namespace Nextouch\Wins\Cron;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Rma\Api\RmaRepositoryInterface;
use Magento\Rma\Model\Rma;
use Nextouch\Rma\Api\Data\RmaInterface;
use Nextouch\Wins\Service\Rma\CreateNewReturn as CreateNewReturnService;
use function Lambdish\Phunctional\each;

class RetryReturnCreation
{
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private RmaRepositoryInterface $rmaRepository;
    private CreateNewReturnService $createNewReturnService;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RmaRepositoryInterface $rmaRepository,
        CreateNewReturnService $createNewReturnService
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->rmaRepository = $rmaRepository;
        $this->createNewReturnService = $createNewReturnService;
    }

    public function execute(): void
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter(RmaInterface::RETURN_SYNC_FAILURES, 0, 'gt')
            ->create();

        $returns = $this->rmaRepository->getList($criteria)->getItems();

        each(fn(Rma $item) => $this->createNewReturnService->create($item), $returns);
    }
}
