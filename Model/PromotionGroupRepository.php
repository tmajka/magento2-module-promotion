<?php declare(strict_types=1);

namespace TMajka\Promotion\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use TMajka\Promotion\Api\Data\PromotionGroupInterface;
use TMajka\Promotion\Api\Data\PromotionGroupSearchResultsInterface;
use TMajka\Promotion\Api\PromotionGroupRepositoryInterface;
use TMajka\Promotion\Model\PromotionGroupFactory;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup as PromotionGroupResourceModel;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use TMajka\Promotion\Api\Data\PromotionGroupSearchResultsInterfaceFactory;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup\Collection;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup\CollectionFactory as PromotionGroupCollectionFactory;

class PromotionGroupRepository implements PromotionGroupRepositoryInterface
{
    public function __construct(
        private readonly PromotionGroupFactory $promotionGroupFactory,
        private readonly PromotionGroupResourceModel $promotionGroupResourceModel,
        private readonly PromotionGroupCollectionFactory $promotionGroupCollectionFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly PromotionGroupSearchResultsInterfaceFactory $searchResultsFactory,

    ) {}

    /**
     * @inheritDoc
     */
    public function save(PromotionGroupInterface $promotionGroup): int
    {
        if ($promotionGroup->getId()) {
            throw new CouldNotSaveException(
                __('You cannot edit promotions group. You can only add a new one')
            );
        }

        try {
            $this->promotionGroupResourceModel->save($promotionGroup);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException( __('Unable to save the object to the database. Please check the data and try again.'));
        }

        return (int) $promotionGroup->getId();
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): PromotionGroupInterface
    {
        $promotionGroup = $this->promotionGroupFactory->create();
        $this->promotionGroupResourceModel->load($promotionGroup, $id);

        if (!$promotionGroup->getId()) {
            throw new NoSuchEntityException(__('The promotion group with ID: %1 doesn\'t exist.', $id));
        }

        return $promotionGroup;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->promotionGroupCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();
        $collection->addPromotionIds();

        /** @var PromotionGroupSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        $promotionGroup = $this->getById($id);

        try {
            $this->promotionGroupResourceModel->delete($promotionGroup);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('The promotion group with ID: %1 couldn\'t be removed.', $promotionGroup->getId()));
        }

        return true;
    }
}
