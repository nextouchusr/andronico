<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Gallery\Video;

use Laminas\Uri\Uri as UriHandler;
use Magento\Framework\Api\Data\ImageContentInterface;
use Magento\Framework\Api\Data\ImageContentInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\DriverInterface;
use Nextouch\ImportExport\Model\Wins\Video;

class ImageContentMapper
{
    private ImageContentInterfaceFactory $imageContentFactory;
    private DriverInterface $driver;
    private UriHandler $uriHandler;

    public function __construct(
        ImageContentInterfaceFactory $imageContentFactory,
        DriverInterface $driver,
        UriHandler $uriHandler
    ) {
        $this->imageContentFactory = $imageContentFactory;
        $this->driver = $driver;
        $this->uriHandler = $uriHandler;
    }

    /**
     * @throws LocalizedException
     */
    public function map(Video $video): ImageContentInterface
    {
        $imageContent = $this->imageContentFactory->create();
        $imageContent->setBase64EncodedData($this->getBase64PreviewImage($video));
        $imageContent->setType('image/jpeg');
        $imageContent->setName('default.jpg');

        return $imageContent;
    }

    /**
     * @throws LocalizedException
     */
    private function getBase64PreviewImage(Video $video): string
    {
        $path = $this->getPreviewImageUrl($video);

        return base64_encode($this->driver->fileGetContents($path));
    }

    /**
     * @throws LocalizedException
     */
    private function getPreviewImageUrl(Video $video): string
    {
        $query = $this->uriHandler->parse($video->getUrl())->getQueryAsArray();

        if (!isset($query['v'])) {
            $text = 'The product video URL %1 does not contain an identifier. Failed to get preview image';
            throw new LocalizedException(__($text, $video->getUrl()));
        }

        return sprintf('https://img.youtube.com/vi/%s/maxresdefault.jpg', $query['v']);
    }
}
