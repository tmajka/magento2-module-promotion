<?php declare(strict_types=1);

namespace TMajka\Promotion\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use TMajka\Promotion\Api\Data\PromotionGroupInterface;

/**
 * Promotion Group CRUD interface.
 * @api
 * @since 1.0.0
 */
interface PromotionGroupRepositoryInterface
{
    /**
     * Create promotion group
     *
     * @param \TMajka\Promotion\Api\Data\PromotionGroupInterface $promotionGroup
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PromotionGroupInterface $promotionGroup): int;

    /**
     * Load promotion group data by given promotion group Identity
     *
     * @param int $id
     * @return \TMajka\Promotion\Api\Data\PromotionGroupInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id): PromotionGroupInterface;


    /**
     * Get promotion group list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TMajka\Promotion\Api\Data\PromotionGroupSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete promotion group by given promotion group Identity
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;
}
