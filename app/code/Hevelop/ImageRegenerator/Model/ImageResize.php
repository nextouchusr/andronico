<?php /** @noinspection PhpDeprecationInspection */
/**
 * @author hevelop_team
 * @copyright Copyright (c) 2021 Hevelop (https://www.hevelop.com)
 * @package nextouch
 */
declare(strict_types=1);

namespace Hevelop\ImageRegenerator\Model;

use Exception;
use Generator;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Image\ParamsBuilder;
use Magento\Catalog\Model\Product\Media\ConfigInterface as MediaConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\Image as ImageResource;
use Magento\Catalog\Model\View\Asset\ImageFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Image;
use Magento\Framework\Image\Factory;
use Magento\Framework\View\ConfigInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\Store\Model\ResourceModel\Store\CollectionFactory;
use Magento\Theme\Model\ResourceModel\Theme\Collection;
use Magento\Theme\Model\Theme;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ImageResize
{
    /** @var Factory */
    private Factory $imageFactory;

    /** @var ImageFactory */
    private ImageFactory $assertImageFactory;

    /** @var Database */
    private Database $fileStorageDatabase;

    /** @var MediaConfigInterface */
    private MediaConfigInterface $imageConfig;

    /** @var ImageResource */
    private ImageResource $productImage;

    /** @var ParamsBuilder */
    private ParamsBuilder $paramsBuilder;

    /** @var Collection */
    private Collection $themeCollection;

    /** @var ConfigInterface */
    private ConfigInterface $viewConfig;

    /** @var CollectionFactory */
    private $storeCollectionFactory;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    /** @var WriteInterface */
    private WriteInterface $mediaDirectory;

    /**
     * imageResize constructor.
     *
     * @param ImageResource $productImage
     * @param CollectionFactory $storeCollectionFactory
     * @param ParamsBuilder $paramsBuilder
     * @param Collection $themeCollection
     * @param ConfigInterface $viewConfig
     * @param Factory $imageFactory
     * @param ImageFactory $assertImageFactory
     * @param Database $fileStorageDatabase
     * @param Filesystem $filesystem
     * @param MediaConfigInterface $imageConfig
     * @param LoggerInterface $logger
     *
     * @throws FileSystemException
     */
    public function __construct(
        ImageResource $productImage,
        CollectionFactory $storeCollectionFactory,
        ParamsBuilder $paramsBuilder,
        Collection $themeCollection,
        ConfigInterface $viewConfig,
        Factory $imageFactory,
        ImageFactory $assertImageFactory,
        Database $fileStorageDatabase,
        Filesystem $filesystem,
        MediaConfigInterface $imageConfig,
        LoggerInterface $logger
    ) {
        $this->productImage = $productImage;
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->paramsBuilder = $paramsBuilder;
        $this->themeCollection = $themeCollection;
        $this->viewConfig = $viewConfig;
        $this->imageFactory = $imageFactory;
        $this->assertImageFactory = $assertImageFactory;
        $this->fileStorageDatabase = $fileStorageDatabase;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->imageConfig = $imageConfig;
        $this->logger = $logger;
    }

    /**
     * @param string $themePath
     * @param int $quality
     * @param array $stores
     * @param bool $overWriteImages
     *
     * @return Generator
     * @throws NotFoundException
     */
    public function resizeFromParams(string $themePath, int $quality, array $stores, bool $overWriteImages): Generator
    {
        $count = $this->productImage->getCountUsedProductImages();
        if (!$count) {
            throw new NotFoundException(__('Cannot resize images - product images not found'));
        }

        $productImages = $this->productImage->getUsedProductImages();
        $viewImages = $this->getViewImages($this->getTheme($themePath), $quality, $stores);

        foreach ($productImages as $image) {
            $error = '';
            $originalImageName = $image['filepath'];

            $mediastoragefilename = $this->imageConfig->getMediaPath($originalImageName);
            $originalImagePath = $this->mediaDirectory->getAbsolutePath($mediastoragefilename);

            if ($this->fileStorageDatabase->checkDbUsage()) {
                $this->fileStorageDatabase->saveFileToFilesystem($mediastoragefilename);
            }
            if ($this->mediaDirectory->isFile($originalImagePath)) {
                foreach ($viewImages as $viewImage) {
                    $this->resize($viewImage, $originalImagePath, $originalImageName, $overWriteImages);
                }
            } else {
                $error = __('Cannot resize image "%1" - original image not found', $originalImagePath);
            }

            yield ['filename' => $originalImageName, 'error' => $error] => $count;
        }
    }

    /**
     * Get view images data from themes.
     *
     * @param Theme $theme
     * @param int $quality
     * @param array $stores
     *
     * @return array
     */
    private function getViewImages(Theme $theme, int $quality, array $stores): array
    {
        $viewImages = [];
        $storeCollection = $this->storeCollectionFactory->create();
        $storeCollection->addFieldToFilter('store_id', ['in' => $stores]);
        $config = $this->viewConfig->getViewConfig(
            [
                'area' => Area::AREA_FRONTEND,
                'themeModel' => $theme,
            ]
        );
        $images = $config->getMediaEntities('Magento_Catalog', ImageHelper::MEDIA_TYPE_CONFIG_NODE);
        foreach ($images as $imageId => $imageData) {
            if(!in_array($imageId, [
                'product_small_image',
                'product_page_main_image',
                'product_page_main_image_default',
                'product_page_image_small',
                'product_page_image_large',
                'product_page_image_medium',
                'product_base_image'
            ])){
                continue;
            }
            foreach ($storeCollection as $store) {
                $data = $this->paramsBuilder->build($imageData, (int)$store->getId(), $theme);
                $data['quality'] = $quality;
                $uniqIndex = $this->getUniqueImageIndex($data);
                $data['id'] = $imageId;
                $viewImages[$uniqIndex] = $data;
            }
        }

        return $viewImages;
    }

    /**
     * Get unique image index.
     *
     * @param array $imageData
     *
     * @return string
     */
    private function getUniqueImageIndex(array $imageData): string
    {
        ksort($imageData);
        unset($imageData['type']);

        // phpcs:disable Magento2.Security.InsecureFunction
        return md5(json_encode($imageData));
    }

    /**
     * @param string $code
     *
     * @return Theme
     */
    private function getTheme(string $code): Theme
    {
        /** @var Theme $theme */
        $theme = $this->themeCollection->addFieldToFilter('code', ['eq' => $code])->getFirstItem();

        if (!$theme->getId()) {
            throw new InvalidArgumentException(__('invalid theme code'));
        }

        return $theme;
    }

    /**
     * Resize image.
     *
     * @param array $imageParams
     * @param string $originalImagePath
     * @param string $originalImageName
     * @param bool $overWriteImages
     */
    private function resize(
        array $imageParams,
        string $originalImagePath,
        string $originalImageName,
        bool $overWriteImages
    ): void {
        unset($imageParams['id']);
        $image = $this->makeImage($originalImagePath, $imageParams);
        $imageAsset = $this->assertImageFactory->create(
            [
                'miscParams' => $imageParams,
                'filePath' => $originalImageName,
            ]
        );

        if (!$overWriteImages && $this->imageAlreadyExists($imageAsset->getPath())) {
            $this->logger->log(Logger::ERROR, 'Skipped: image already exists '
                . $imageAsset->getPath());

            return;
        }

        if ($imageParams['image_width'] !== null && $imageParams['image_height'] !== null) {
            $image->resize($imageParams['image_width'], $imageParams['image_height']);
        }

        if (isset($imageParams['watermark_file'])) {
            if ($imageParams['watermark_height'] !== null) {
                $image->setWatermarkHeight($imageParams['watermark_height']);
            }

            if ($imageParams['watermark_width'] !== null) {
                $image->setWatermarkWidth($imageParams['watermark_width']);
            }

            if ($imageParams['watermark_position'] !== null) {
                $image->setWatermarkPosition($imageParams['watermark_position']);
            }

            if ($imageParams['watermark_image_opacity'] !== null) {
                $image->setWatermarkImageOpacity($imageParams['watermark_image_opacity']);
            }

            try {
                $image->watermark($this->getWatermarkFilePath($imageParams['watermark_file']));
            } catch (Exception $e) {
                $this->logger->log(Logger::ERROR, "Error: {$e->getMessage()}" .
                    " Trace: {$e->getTraceAsString()}");
            }
        }

        $image->save($imageAsset->getPath());

        if ($this->fileStorageDatabase->checkDbUsage()) {
            $mediastoragefilename = $this->mediaDirectory->getRelativePath($imageAsset->getPath());
            $this->fileStorageDatabase->saveFile($mediastoragefilename);
        }
    }

    /**
     * Make image.
     *
     * @param string $originalImagePath
     * @param array $imageParams
     *
     * @return Image
     */
    private function makeImage(string $originalImagePath, array $imageParams): Image
    {
        $image = $this->imageFactory->create($originalImagePath);
        $image->keepAspectRatio($imageParams['keep_aspect_ratio']);
        $image->keepFrame($imageParams['keep_frame']);
        $image->keepTransparency($imageParams['keep_transparency']);
        $image->constrainOnly($imageParams['constrain_only']);
        $image->backgroundColor($imageParams['background']);
        $image->quality($imageParams['quality']);

        return $image;
    }

    /**
     * @param $path
     *
     * @return bool
     */
    private function imageAlreadyExists($path): bool
    {
        return $this->mediaDirectory->isFile($path);
    }

    /**
     * Returns watermark file absolute path
     *
     * @param string $file
     *
     * @return string
     */
    private function getWatermarkFilePath(string $file): string
    {
        $path = $this->imageConfig->getMediaPath('/watermark/' . $file);

        return $this->mediaDirectory->getAbsolutePath($path);
    }
}
