<?php declare(strict_types=1);

namespace TMajka\Promotion\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Promotion extends AbstractDb
{
    const MAIN_TABLE = 'tmajka_promotion';
    const ID_FIELD_NAME = 'promotion_id';
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
