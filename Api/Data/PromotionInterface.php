<?php declare(strict_types=1);

namespace TMajka\Promotion\Api\Data;

interface PromotionInterface
{
    const ID = 'promotion_id';
    const NAME = 'name';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const PROMOTION_GROUP_IDS = 'promotion_group_ids';

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
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @return mixed
     */
    public function getPromotionGroupIds();
}
