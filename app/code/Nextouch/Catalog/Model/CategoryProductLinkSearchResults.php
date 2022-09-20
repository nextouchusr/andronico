<?php
declare(strict_types=1);

namespace Nextouch\Catalog\Model;

use Magento\Framework\Api\SearchResults;
use Nextouch\Catalog\Api\Data\CategoryProductLinkSearchResultsInterface;

class CategoryProductLinkSearchResults extends SearchResults implements CategoryProductLinkSearchResultsInterface
{
}
