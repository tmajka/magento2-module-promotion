<?php declare(strict_types=1);

namespace TMajka\Promotion\Model;

use Magento\Framework\Model\AbstractModel;
use TMajka\Promotion\Api\Data\PromotionGroupRelationInterface;

class PromotionGroupRelation extends AbstractModel implements PromotionGroupRelationInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\PromotionGroupRelation::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function getPromotionId()
    {
        return $this->getData(self::PROMOTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPromotionId($promotionId)
    {
        return $this->setData(self::PROMOTION_ID, $promotionId);
    }

    /**
     * @inheritDoc
     */
    public function getPromotionGroupId()
    {
        return $this->getData(self::PROMOTION_GROUP_ID);
    }

    /**
     * @inheritDoc
     */
    public function setPromotionGroupId($promotionGroupId)
    {
        return $this->setData(self::PROMOTION_GROUP_ID, $promotionGroupId);
    }
}
