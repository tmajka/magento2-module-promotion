<?php declare(strict_types=1);

namespace TMajka\Promotion\Model;

use _PHPStan_532094bc1\Nette\Neon\Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use TMajka\Promotion\Api\Data\PromotionGroupRelationInterface;
use TMajka\Promotion\Api\PromotionGroupRelationRepositoryInterface;
use TMajka\Promotion\Model\PromotionGroupRelationFactory;
use TMajka\Promotion\Model\PromotionGroupRepository;
use TMajka\Promotion\Model\PromotionRepository;
use TMajka\Promotion\Model\ResourceModel\PromotionGroupRelation as PromotionGroupRelationResourceModel;

class PromotionGroupRelationRepository implements PromotionGroupRelationRepositoryInterface
{
    public function __construct(
        private readonly PromotionGroupRelationResourceModel $relationResourceModel,
        private readonly PromotionGroupRepository $promotionGroupRepository,
        private readonly PromotionRepository $promotionRepository,
        private readonly PromotionGroupRelationFactory $relationFactory,
    ) {}
    /**
     * @inheritDoc
     */
    public function save(PromotionGroupRelationInterface $relation)
    {
        if ($relation->getId()) {
            throw new CouldNotSaveException(
                __('You cannot edit assign promotion to promotion groups. You can only add a new one.')
            );
        }

        $this->promotionGroupRepository->getById($relation->getPromotionGroupId());
        $this->promotionRepository->getById($relation->getPromotionId());

        if ($this->relationResourceModel->getRelationIdByGroupIdAndPromotionId(
            $relation->getPromotionGroupId(),
            $relation->getPromotionId())) {
            throw new CouldNotSaveException(
                __('Such assignment of promotions to promotion groups already exists.')
            );
        }

        try {
            $this->relationResourceModel->save($relation);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Unable to save the object to the database. Please check the data and try again.'));
        }

        return !empty($relation->getId());
    }

    /**
     * @inheritDoc
     */
    public function deleteByIds($promotionId, $promotionGroupId)
    {
       $relationId = (int) $this->relationResourceModel->getRelationIdByGroupIdAndPromotionId($promotionGroupId, $promotionId);

       if(empty($relationId)) {
           throw new NoSuchEntityException(
               __('Such assignment of promotions to promotion groups already not exists.')
           );
       }

        $relation = $this->relationFactory->create();
        $this->relationResourceModel->load($relation, $relationId);

        try {
            $this->relationResourceModel->delete($relation);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('The relation couldn\'t be removed.' . $exception->getMessage()));
        }

        return true;
    }
}
