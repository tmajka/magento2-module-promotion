<?php declare(strict_types=1);

namespace TMajka\Promotion\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use TMajka\Promotion\Api\Data\PromotionInterface;
use TMajka\Promotion\Api\Data\PromotionSearchResultsInterface;
use TMajka\Promotion\Api\Data\PromotionSearchResultsInterfaceFactory;
use TMajka\Promotion\Api\PromotionRepositoryInterface;
use TMajka\Promotion\Model\PromotionFactory;
use TMajka\Promotion\Model\ResourceModel\Promotion\Collection;
use TMajka\Promotion\Model\ResourceModel\Promotion\CollectionFactory as PromotionCollectionFactory;
use TMajka\Promotion\Model\ResourceModel\Promotion as PromotionResourceModel;

class PromotionRepository implements PromotionRepositoryInterface
{
    public function __construct(
        private PromotionCollectionFactory $promotionCollectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        private PromotionFactory $promotionFactory,
        private PromotionResourceModel $promotionResourceModel,
        private PromotionSearchResultsInterfaceFactory $searchResultsFactory,

    ) {
    }

    public function getById(int $id): PromotionInterface
    {
        $promotion = $this->promotionFactory->create();
        $this->promotionResourceModel->load($promotion, $id);

        if (!$promotion->getId()) {
            throw new NoSuchEntityException(
                __('The promotion with ID: %1 doesn\'t exist.', $id)
            );
        }

        return $promotion;
    }

    public function save(PromotionInterface $promotion): int
    {
        if ($promotion->getId()) {
            throw new CouldNotSaveException(
                __('You cannot edit promotions. You can only add a new one')
            );
        }

        try {
            $this->promotionResourceModel->save($promotion);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Unable to save the object to the database. Please check the data and try again.')
            );
        }

        return (int) $promotion->getId();
    }

    public function deleteById(int $id): bool
    {
        $promotion = $this->getById($id);

        try {
            $this->promotionResourceModel->delete($promotion);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('The promotion with ID: %1 couldn\'t be removed.', $promotion->getId()));
        }

        return true;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        /** @var Collection $collection */
        $collection = $this->promotionCollectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);
        $collection->load();
        $collection->addPromotionGroupIds();

        /** @var PromotionSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
