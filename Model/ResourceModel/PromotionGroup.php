<?php declare(strict_types=1);

namespace TMajka\Promotion\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PromotionGroup extends AbstractDb
{
    const MAIN_TABLE = 'tmajka_promotion_group';
    const ID_FIELD_NAME = 'promotion_group_id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
