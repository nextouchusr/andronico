<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Nextouch\Catalog\Api\Data\CategoryInterface;

class Category extends \Magento\Catalog\Model\Category implements CategoryInterface
{
    public function getExternalCategoryId(): string
    {
        return (string) $this->getData(self::EXTERNAL_CATEGORY_ID);
    }

    public function setExternalCategoryId(string $externalCategoryId): self
    {
        $this->setData(self::EXTERNAL_CATEGORY_ID, $externalCategoryId);

        return $this;
    }
}
