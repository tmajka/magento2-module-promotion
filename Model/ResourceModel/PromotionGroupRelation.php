<?php declare(strict_types=1);

namespace TMajka\Promotion\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PromotionGroupRelation extends AbstractDb
{
    const MAIN_TABLE = 'tmajka_promotion_group_relation';
    const ID_FIELD_NAME = 'relation_id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    /**
     * @param $promotionGroupId
     * @param $promotionId
     * @return string
     * @throws LocalizedException
     */
    public function getRelationIdByGroupIdAndPromotionId($promotionGroupId, $promotionId)
    {
        $select = $this->getConnection()->select()
            ->from($this->getMainTable())
            ->where("promotion_id = ?", $promotionId)
            ->where("promotion_group_id = ?", $promotionGroupId)
            ->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}
