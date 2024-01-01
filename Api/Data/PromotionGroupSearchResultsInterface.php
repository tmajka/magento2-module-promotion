<?php declare(strict_types=1);

namespace TMajka\Promotion\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PromotionGroupSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \TMajka\Promotion\Api\Data\PromotionGroupInterface[]
     */
    public function getItems();

    /**
     * @param \TMajka\Promotion\Api\Data\PromotionGroupInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
