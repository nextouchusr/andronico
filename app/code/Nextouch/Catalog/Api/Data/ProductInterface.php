<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Api\Data;

/**
 * @api
 */
interface ProductInterface extends \Magento\Catalog\Api\Data\ProductInterface
{
    public const ALTERNATIVE_CODE = 'alternative_code';
    public const BRAND = 'brand';

    /**
     * @return string
     */
    public function getAlternativeCode(): string;

    /**
     * @param string $alternativeCode
     * @return ProductInterface
     */
    public function setAlternativeCode(string $alternativeCode): self;

    /**
     * @return int
     */
    public function getBrand(): int;

    /**
     * @param int $brand
     * @return ProductInterface
     */
    public function setBrand(int $brand): self;
}
