<?php
declare(strict_types=1);

namespace Nextouch\Wins\Service\Rma;

use Magento\Framework\Exception\LocalizedException;
use Magento\Rma\Api\RmaRepositoryInterface;
use Magento\Rma\Model\Rma;
use Nextouch\Rma\Api\Data\RmaInterface;
use Nextouch\Wins\Api\AuthManagementInterface;
use Nextouch\Wins\Api\RmaRepositoryInterface as WinsRmaRepositoryInterface;
use Nextouch\Wins\Helper\WinsConfig;
use Nextouch\Wins\Model\Auth\LoginInfo;
use Nextouch\Wins\Model\Request\Auth\Authorize;
use Nextouch\Wins\Model\Request\Rma\CreateReturn;
use Psr\Log\LoggerInterface;

class CreateNewReturn
{
    private RmaRepositoryInterface $rmaRepository;
    private AuthManagementInterface $authManagement;
    private WinsRmaRepositoryInterface $winsRmaRepository;
    private WinsConfig $config;
    private LoggerInterface $logger;

    public function __construct(
        RmaRepositoryInterface $rmaRepository,
        AuthManagementInterface $authManagement,
        WinsRmaRepositoryInterface $winsRmaRepository,
        WinsConfig $config,
        LoggerInterface $logger
    ) {
        $this->rmaRepository = $rmaRepository;
        $this->authManagement = $authManagement;
        $this->winsRmaRepository = $winsRmaRepository;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function create(Rma $return): void
    {
        try {
            $isSuccess = $this->createReturn($return);

            if ($isSuccess) {
                $return->setData(RmaInterface::RETURN_SYNC_FAILURES, 0);
            } else {
                $failures = (int) $return->getData(RmaInterface::RETURN_SYNC_FAILURES);
                $return->setData(RmaInterface::RETURN_SYNC_FAILURES, ++$failures);
            }

            $this->rmaRepository->save($return);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function createReturn(Rma $return): bool
    {
        $authorizeReq = new Authorize($this->config->getAuthUsername(), $this->config->getAuthPassword());
        $authorizeRes = $this->authManagement->authorize($authorizeReq);

        $loginInfo = LoginInfo::fromArray([
            'user' => $this->config->getMagentoUsername(),
            'password' => $this->config->getMagentoPassword(),
        ]);

        $createReturn = new CreateReturn(
            $authorizeRes->getAccessToken(),
            $loginInfo,
            $return
        );

        return $this->winsRmaRepository->create($createReturn);
    }
}
