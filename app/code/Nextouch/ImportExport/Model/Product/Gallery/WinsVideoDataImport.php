<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Product\Gallery;

use Magento\Catalog\Api\ProductAttributeMediaGalleryManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\ImportExport\Api\EntityDataImportInterface;
use Nextouch\ImportExport\Model\Wins\Product\Gallery\Video\VideoGalleryMapper;
use Nextouch\ImportExport\Model\Wins\Video;
use Psr\Log\LoggerInterface;

class WinsVideoDataImport implements EntityDataImportInterface
{
    private ProductAttributeMediaGalleryManagementInterface $mediaGalleryManagement;
    private VideoGalleryMapper $videoGalleryMapper;
    private LoggerInterface $logger;

    public function __construct(
        ProductAttributeMediaGalleryManagementInterface $mediaGalleryManagement,
        VideoGalleryMapper $videoGalleryMapper,
        LoggerInterface $logger
    ) {
        $this->mediaGalleryManagement = $mediaGalleryManagement;
        $this->videoGalleryMapper = $videoGalleryMapper;
        $this->logger = $logger;
    }

    public function importData(\IteratorAggregate $data): void
    {
        \Lambdish\Phunctional\each(fn(Video $item) => $this->saveProductVideo($item), $data);
    }

    private function saveProductVideo(Video $video)
    {
        try {
            $videoGallery = $this->videoGalleryMapper->map($video);
            $this->mediaGalleryManagement->create($video->getProductCode(), $videoGallery);
        } catch (LocalizedException $e) {
            $text = 'Failed to import video %1 for the product %2. Error: %3';
            $message = __($text, $video->getTitle(), $video->getProductCode(), $e->getMessage());
            $this->logger->error($message);
        }
    }
}
