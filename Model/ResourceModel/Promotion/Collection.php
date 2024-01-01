<?php declare(strict_types=1);

namespace TMajka\Promotion\Model\ResourceModel\Promotion;

use TMajka\Promotion\Model\Promotion;
use TMajka\Promotion\Model\ResourceModel\Promotion as PromotionResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    private string $_promotionGroupRelationTable;

    protected function _construct()
    {
        $this->_init(Promotion::class, PromotionResourceModel::class);
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
    public function addPromotionGroupIds()
    {
        if ($this->getFlag('group_ids_added')) {
            return $this;
        }
        $ids = array_keys($this->_items);
        if (empty($ids)) {
            return $this;
        }

        $select = $this->getConnection()->select();

        $select->from($this->_promotionGroupRelationTable, ['promotion_id', 'promotion_group_id']);
        $select->where('promotion_id IN (?)', $ids, \Zend_Db::INT_TYPE);

        $data = $this->getConnection()->fetchAll($select);

        $promotionGroupIds = [];
        foreach ($data as $info) {
            if (isset($promotionGroupIds[$info['promotion_id']])) {
                $promotionGroupIds[$info['promotion_id']][] = $info['promotion_group_id'];
            } else {
                $promotionGroupIds[$info['promotion_id']] = [$info['promotion_group_id']];
            }
        }

        foreach ($this->getItems() as $item) {
            $promotionId = $item->getId();
            if (isset($promotionGroupIds[$promotionId])) {
                $item->setPromotionGroupIds($promotionGroupIds[$promotionId]);
            } else {
                $item->setPromotionGroupIds([]);
            }
        }

        $this->setFlag('group_ids_added', true);
        return $this;
    }
}
