<?php declare(strict_types=1);

namespace TMajka\Promotion\Api\Data;

interface PromotionGroupRelationInterface
{
    const ID = 'relation_id';
    const PROMOTION_ID = 'promotion_id';
    const PROMOTION_GROUP_ID = 'promotion_group_id';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getPromotionId();

    /**
     * @param int $promotionId
     * @return void
     */
    public function setPromotionId($promotionId);

    /**
     * @return int
     */
    public function getPromotionGroupId();

    /**
     * @param int $promotionGroupId
     * @return void
     */
    public function setPromotionGroupId($promotionGroupId);
}
