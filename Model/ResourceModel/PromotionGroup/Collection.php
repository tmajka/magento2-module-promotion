<?php declare(strict_types=1);

namespace TMajka\Promotion\Model\ResourceModel\PromotionGroup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use TMajka\Promotion\Model\PromotionGroup;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup as PromotionGroupResourceModel;

class Collection extends AbstractCollection
{
    private string $_promotionGroupRelationTable;

    protected function _construct()
    {
        $this->_init(PromotionGroup::class, PromotionGroupResourceModel::class);
        $this->_initTables();
    }

    /**
     * Define promotion group relation table
     *
     * @return void
     */
    protected function _initTables()
    {
        $this->_promotionGroupRelationTable = $this->getResource()->getTable('tmajka_promotion_group_relation');
    }

    /**
     * Add promotion group ids to loaded items
     *
     * @return $this
     */
    public function addPromotionIds()
    {
        if ($this->getFlag('promotion_ids_added')) {
            return $this;
        }
        $ids = array_keys($this->_items);
        if (empty($ids)) {
            return $this;
        }

        $select = $this->getConnection()->select();

        $select->from($this->_promotionGroupRelationTable, ['promotion_id', 'promotion_group_id']);
        $select->where('promotion_group_id IN (?)', $ids, \Zend_Db::INT_TYPE);

        $data = $this->getConnection()->fetchAll($select);

        $promotionIds = [];
        foreach ($data as $info) {
            if (isset($promotionIds[$info['promotion_group_id']])) {
                $promotionIds[$info['promotion_group_id']][] = $info['promotion_id'];
            } else {
                $promotionIds[$info['promotion_group_id']] = [$info['promotion_id']];
            }
        }

        foreach ($this->getItems() as $item) {
            $promotionGroupId = $item->getId();
            if (isset($promotionIds[$promotionGroupId])) {
                $item->setPromotionIds($promotionIds[$promotionGroupId]);
            } else {
                $item->setPromotionIds([]);
            }
        }

        $this->setFlag('promotion_ids_added', true);
        return $this;
    }
}
