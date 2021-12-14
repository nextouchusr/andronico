<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api\Data;

/**
 * @api
 */
interface CategoryInterface extends \Magento\Catalog\Api\Data\CategoryInterface
{
    public const EXTERNAL_CATEGORY_ID = 'external_category_id';

    /**
     * @return string
     */
    public function getExternalCategoryId(): string;

    /**
     * @param string $externalCategoryId
     * @return CategoryInterface
     */
    public function setExternalCategoryId(string $externalCategoryId): self;
}
