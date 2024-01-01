<?php declare(strict_types=1);

namespace TMajka\Promotion\Api;

use TMajka\Promotion\Api\Data\PromotionGroupRelationInterface;

/**
 * Promotion Group Relation Management interface.
 * @api
 * @since 1.0.0
 */
interface PromotionGroupRelationRepositoryInterface
{
    /**
     * Assign a promotion to the promotion group
     *
     * @param \TMajka\Promotion\Api\Data\PromotionGroupRelationInterface $relation
     * @return bool will return True if assigned
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(PromotionGroupRelationInterface $relation);

    /**
     * Remove the promotion assignment from the promotion group by promotion id and group promotion id
     *
     * @param int $promotionId
     * @param string $promotionGroupId
     * @return bool
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function deleteByIds($promotionId, $promotionGroupId);
}
