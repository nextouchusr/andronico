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

    public function getAlternativeCode(): string;

    public function setAlternativeCode(string $alternativeCode): self;

    public function getBrand(): int;

    public function setBrand(int $brand): self;
}
