<?php declare(strict_types=1);

namespace TMajka\Promotion\Model;

use Magento\Framework\Api\SearchResults;
use TMajka\Promotion\Api\Data\PromotionSearchResultsInterface;

class PromotionSearchResults extends SearchResults implements PromotionSearchResultsInterface
{
}
