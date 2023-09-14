<?php

declare(strict_types=1);

namespace Elightwalk\AmastyGiftCard\Model\Image;

use Amasty\GiftCard\Api\Data\ImageInterface;
use Amasty\GiftCard\Utils\FileUpload;
use Amasty\GiftCard\Model\Image\OutputBuilderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

/**
 * @api
 */
class EmailImageProvider extends \Amasty\GiftCard\Model\Image\EmailImageProvider
{
    /**
     * @var FileUpload
     */
    private $fileUpload;

    /**
     * @var OutputBuilderFactory
     */
    private $outputBuilderFactory;

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @var Filesystem|null
     */
    private $filesystem;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * __construct
     *
     * @param FileUpload $fileUpload
     * @param File|null $file
     * @param OutputBuilderFactory $outputBuilderFactory
     * @param Mime $mime
     * @param Filesystem|null $filesystem
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        FileUpload $fileUpload,
        File $file = null, //@deprecated
        OutputBuilderFactory $outputBuilderFactory,
        Mime $mime,
        Filesystem $filesystem = null,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct(
            $fileUpload,
            $file,
            $outputBuilderFactory,
            $mime,
            $filesystem
        );

        $this->fileUpload = $fileUpload;
        $this->outputBuilderFactory = $outputBuilderFactory;
        $this->mime = $mime;
        $this->filesystem = $filesystem ?: ObjectManager::getInstance()->get(Filesystem::class);
        $this->storeManager = $storeManager;
    }

    public function get(ImageInterface $image, string $code): string
    {
        $imagePath = $this->fileUpload->getImagePath($image);
        $media = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        if (!$media->isExist($imagePath)) {
            return '';
        }

        $fileContent = $media->getDriver()->fileGetContents($imagePath);
        $result = '<div style="overflow:hidden;position:relative;height:' . $image->getHeight()
            . 'px;width:' . $image->getWidth() . 'px;"><img style="height:100%;width:100%;" src="data:'
            . $this->mime->getMimeType($imagePath)
            . ';base64,' . base64_encode($fileContent) . '">';

        // Custom Code Starts
        $mediaUrl   = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        if ($imagePath && is_string($imagePath)) {
            $explodeUrl = explode('media', $imagePath);
            if ($explodeUrl && isset($explodeUrl[1])) {
                $url = $mediaUrl . $explodeUrl[1];

                if ($url) {
                    $result = '<div style="overflow:hidden;position:relative;height:' . $image->getHeight() . 'px;width:' . $image->getWidth() . 'px;"><img style="height:100%;width:100%;" src="' . $url . '">';
                }
            }
        }
        // Custom Code Ends

        if (!$image->isUserUpload() && $image->getImageElements()) {
            $result .= $this->outputBuilderFactory->create(OutputBuilderFactory::HTML_BUILDER, ['code' => $code])
                ->build($image->getImageElements());
        }
        $result .= '</div>';

        return $result;
    }
}
