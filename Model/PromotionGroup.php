<?php declare(strict_types=1);

namespace TMajka\Promotion\Model;

use Magento\Framework\Model\AbstractModel;
use TMajka\Promotion\Api\Data\PromotionGroupInterface;

class PromotionGroup extends AbstractModel implements PromotionGroupInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\PromotionGroup::class);
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
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getPromotionIds()
    {
        return $this->getData(self::PROMOTION_IDS);
    }
}
