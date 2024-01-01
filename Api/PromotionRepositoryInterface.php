<?php declare(strict_types=1);

namespace TMajka\Promotion\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use TMajka\Promotion\Api\Data\PromotionInterface;

/**
 * Promotion CRUD interface.
 * @api
 * @since 1.0.0
 */
interface PromotionRepositoryInterface
{
    /**
     * Create promotion
     *
     * @param \TMajka\Promotion\Api\Data\PromotionInterface $promotion
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(PromotionInterface $promotion): int;

    /**
     * Load promotion data by given promotion identity
     *
     * @param int $id
     * @return \TMajka\Promotion\Api\Data\PromotionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $id): PromotionInterface;


    /**
     * Get promotion list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TMajka\Promotion\Api\Data\PromotionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete promotion by given promotion identity
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $id): bool;
}
