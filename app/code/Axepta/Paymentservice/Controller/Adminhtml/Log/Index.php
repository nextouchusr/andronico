<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 25.10.18
 * Time: 15:45
 */

namespace Axepta\Paymentservice\Controller\Adminhtml\Log;

use Axepta\Paymentservice\Model\Ui\ConfigProvider;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{

    /**
     * @var DirectoryList
     */
    private $directoryList;

    public function __construct(Context $context, DirectoryList $directoryList)
    {
        parent::__construct($context);
        $this->directoryList = $directoryList;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $fileCollection = '';


        $dateFrom = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 00:00:00', $this->getRequest()->getParam('date_from')));
        $dateTo = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 23:59:59', $this->getRequest()->getParam('date_to')));

        if ($dateFrom > $dateTo) {
            $dateFrom = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 00:00:00', $this->getRequest()->getParam('date_to')));
        }

        $iterateDateFrom = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 00:00:00', $this->getRequest()->getParam('date_from')));
        $iterateDateTo = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 23:59:59', $this->getRequest()->getParam('date_to')));

        if ($iterateDateFrom > $iterateDateTo) {
            $iterateDateFrom = \DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%s 00:00:00', $this->getRequest()->getParam('date_to')));
        }


        $logBasePath = $this->directoryList->getPath('log');

        if (is_dir($logBasePath)) {

            while ($iterateDateFrom <= $iterateDateTo) {
                $filePath = sprintf('%s/%s-%s.log', $logBasePath, ConfigProvider::CODE, $iterateDateFrom->format('Y_m_d'));

                if (is_file($filePath)) {
                    if (!empty($fileCollection)) {
                        $fileCollection .= "\n\n";
                    }
                    $fileCollection .= sprintf("***** Log date: %s", $iterateDateFrom->format('Y-m-d'));
                    $fileCollection .= "\n\n";
                    $fileCollection .= @file_get_contents($filePath);
                    $fileCollection .= "\n\n\n\n";
                }
                $iterateDateFrom->modify('+1 day');
            }
        }
        if (empty($fileCollection)) {
            $this->messageManager->addErrorMessage(__(sprintf('No logs found between %s - %s', $this->getRequest()->getParam('date_from'), $this->getRequest()->getParam('date_to'))));

            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        header("Expires: 0");
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
        header("Pragma: no-cache");
        header("Content-type: text/html");
        header("Content-Disposition:attachment; filename=" . ConfigProvider::CODE . "-log.txt");
        header("Content-Length: " . strlen($fileCollection));

        echo $fileCollection;
        exit();
    }
}
