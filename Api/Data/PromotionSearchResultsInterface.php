<?php declare(strict_types=1);

namespace TMajka\Promotion\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PromotionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \TMajka\Promotion\Api\Data\PromotionInterface[]
     */
    public function getItems();

    /**
     * @param \TMajka\Promotion\Api\Data\PromotionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
