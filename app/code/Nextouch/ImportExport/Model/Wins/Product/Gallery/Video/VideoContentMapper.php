<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Product\Gallery\Video;

use Magento\Framework\Api\Data\VideoContentInterface;
use Magento\Framework\Api\Data\VideoContentInterfaceFactory;
use Nextouch\ImportExport\Model\Wins\Video;

class VideoContentMapper
{
    private VideoContentInterfaceFactory $videoContentFactory;

    public function __construct(VideoContentInterfaceFactory $videoContentFactory)
    {
        $this->videoContentFactory = $videoContentFactory;
    }

    public function map(Video $video): VideoContentInterface
    {
        $videoContent = $this->videoContentFactory->create();
        $videoContent->setMediaType('external-video');
        $videoContent->setVideoProvider('youtube');
        $videoContent->setVideoTitle($video->getTitle());
        $videoContent->setVideoUrl($video->getUrl());

        return $videoContent;
    }
}
