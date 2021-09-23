<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Gallery\Video;

use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryExtensionFactory;
use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface;
use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Nextouch\ImportExport\Model\Wins\Video;

class VideoGalleryMapper
{
    private ProductAttributeMediaGalleryEntryInterfaceFactory $mediaGalleryEntryFactory;
    private ProductAttributeMediaGalleryEntryExtensionFactory $mediaGalleryEntryExtensionFactory;
    private ImageContentMapper $imageContentMapper;
    private VideoContentMapper $videoContentMapper;

    public function __construct(
        ProductAttributeMediaGalleryEntryInterfaceFactory $mediaGalleryEntryFactory,
        ProductAttributeMediaGalleryEntryExtensionFactory $mediaGalleryEntryExtensionFactory,
        ImageContentMapper $imageContentMapper,
        VideoContentMapper $videoContentMapper
    ) {
        $this->mediaGalleryEntryFactory = $mediaGalleryEntryFactory;
        $this->mediaGalleryEntryExtensionFactory = $mediaGalleryEntryExtensionFactory;
        $this->imageContentMapper = $imageContentMapper;
        $this->videoContentMapper = $videoContentMapper;
    }

    /**
     * @throws LocalizedException
     */
    public function map(Video $video): ProductAttributeMediaGalleryEntryInterface
    {
        $imageContent = $this->imageContentMapper->map($video);
        $videoContent = $this->videoContentMapper->map($video);

        $videoGallery = $this->mediaGalleryEntryFactory->create();
        $videoGallery->setDisabled(false);
        $videoGallery->setFile('default.jpg');
        $videoGallery->setLabel($video->getTitle());
        $videoGallery->setMediaType('external-video');
        $videoGallery->setContent($imageContent);

        $mediaGalleryEntryExtension = $this->mediaGalleryEntryExtensionFactory->create();
        $mediaGalleryEntryExtension->setVideoContent($videoContent);
        $videoGallery->setExtensionAttributes($mediaGalleryEntryExtension);

        return $videoGallery;
    }
}
