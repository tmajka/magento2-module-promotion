<?php
namespace TMajka\Promotion\Test\Unit\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TMajka\Promotion\Api\Data\PromotionSearchResultsInterface;
use TMajka\Promotion\Api\Data\PromotionSearchResultsInterfaceFactory;
use TMajka\Promotion\Model\Promotion;
use TMajka\Promotion\Model\PromotionFactory;
use TMajka\Promotion\Model\PromotionRepository;
use TMajka\Promotion\Model\ResourceModel\Promotion as PromotionResourceModel;
use TMajka\Promotion\Model\ResourceModel\Promotion\Collection;
use TMajka\Promotion\Model\ResourceModel\Promotion\CollectionFactory;

/**
 * @covers \TMajka\Promotion\Model\PromotionRepository
 */
class PromotionRepositoryTest extends TestCase
{
    /**
     * Mock promotionCollection
     *
     * @var Collection|MockObject
     */
    private $promotionCollection;

    /**
     * Mock promotionCollectionFactory
     *
     * @var CollectionFactory|MockObject
     */
    private $promotionCollectionFactory;

    /**
     * Mock collectionProcessor
     *
     * @var CollectionProcessorInterface|MockObject
     */
    private $collectionProcessor;

    /**
     * Mock promotion model
     *
     * @var Promotion|MockObject
     */
    private $promotion;

    /**
     * Mock promotionFactory
     *
     * @var PromotionFactory|MockObject
     */
    private $promotionFactory;

    /**
     * Mock promotionResourceModel
     *
     * @var PromotionResourceModel|MockObject
     */
    private $promotionResourceModel;

    /**
     * Mock searchResults
     *
     * @var PromotionSearchResultsInterface|MockObject
     */
    private $searchResults;

    /**
     * Mock searchResultsFactory
     *
     * @var PromotionSearchResultsInterfaceFactory|MockObject
     */
    private $searchResultsFactory;

    /**
     * Object to test
     *
     * @var PromotionRepository
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->promotionCollection = $this->createMock(Collection::class);
        $this->promotionCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->promotionCollectionFactory->method('create')->willReturn($this->promotionCollection);
        $this->collectionProcessor = $this->createMock(CollectionProcessorInterface::class);
        $this->promotion = $this->createMock(Promotion::class);
        $this->promotionFactory = $this->createMock(PromotionFactory::class);
        $this->promotionFactory->method('create')->willReturn($this->promotion);
        $this->promotionResourceModel = $this->createMock(PromotionResourceModel::class);
        $this->searchResults = $this->createMock(PromotionSearchResultsInterface::class);
        $this->searchResultsFactory = $this->createMock(PromotionSearchResultsInterfaceFactory::class);
        $this->searchResultsFactory->method('create')->willReturn($this->searchResults);
        $this->testObject = new PromotionRepository(
                $this->promotionCollectionFactory,
                $this->collectionProcessor,
                $this->promotionFactory,
                $this->promotionResourceModel,
                $this->searchResultsFactory
        );
    }

    public function testSave(): void
    {
        $this->promotion->expects($this->exactly(2))->method('getId')->willReturnOnConsecutiveCalls(null, 7);

        $this->promotionResourceModel->expects($this->once())
            ->method('save')
            ->with($this->promotion)
            ->willReturnSelf();

        $this->assertEquals(7, $this->testObject->save($this->promotion));
    }

    public function testOnlyAddSaveException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage("You cannot edit promotions. You can only add a new one");

        $this->promotion->expects($this->once())->method('getId')->willReturn(1);

        $this->testObject->save($this->promotion);
    }

    public function testSaveMainException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage('Unable to save the object to the database. Please check the data and try again.');

        $this->promotion->expects($this->once())->method('getId')->willReturn(null);

        $this->promotionResourceModel
            ->expects($this->once())
            ->method('save')
            ->with($this->promotion)->willThrowException(new \Exception());

        $this->testObject->save($this->promotion);
    }

    public function testGetById(): void
    {
        $promotionId = '123';

        $this->promotion->expects($this->once())
            ->method('getId')
            ->willReturn($promotionId);
        $this->promotionResourceModel->expects($this->once())
            ->method('load')
            ->with($this->promotion, $promotionId)
            ->willReturn($this->promotion);

        $this->assertEquals($this->promotion, $this->testObject->getById($promotionId));
    }

    public function testGetByIdException(): void
    {
        $this->expectException('Magento\Framework\Exception\NoSuchEntityException');
        $this->expectExceptionMessage("The promotion with ID: 123 doesn't exist.");
        $promotionId = '123';

        $this->promotion->expects($this->once())
            ->method('getId')
            ->willReturn(false);
        $this->promotionResourceModel->expects($this->once())
            ->method('load')
            ->with($this->promotion, $promotionId)
            ->willReturn($this->promotion);
        $this->testObject->getById($promotionId);
    }

    public function testDeleteById(): void
    {
        $promotionId = '123';

        $this->promotion->expects($this->once())
            ->method('getId')
            ->willReturn($promotionId);

        $this->promotionResourceModel->expects($this->once())
            ->method('load')
            ->with($this->promotion, $promotionId)
            ->willReturn($this->promotion);

        $this->promotionResourceModel->expects($this->once())
            ->method('delete')
            ->with($this->promotion);
        $this->assertTrue($this->testObject->deleteById($promotionId));
    }

    public function testUnableDeletePromotion(): void
    {
        $promotionId = '7';
        $this->expectException('Magento\Framework\Exception\CouldNotDeleteException');
        $this->expectExceptionMessage('The promotion with ID: 7 couldn\'t be removed.');
        $this->promotion->expects($this->exactly(2))->method('getId')->willReturn($promotionId);
        $this->promotionResourceModel
            ->expects($this->once())
            ->method('delete')
            ->with($this->promotion)->willThrowException(new \Exception());

        $this->testObject->deleteById($promotionId);
    }

    public function testGetList(): void
    {
        $total = 10;

        /** @var SearchCriteriaInterface $criteria */
        $criteria = $this->getMockBuilder(SearchCriteriaInterface::class)
            ->getMock();

        $this->promotionCollection->addItem($this->promotion);
        $this->promotionCollection->expects($this->once())
            ->method('getSize')
            ->willReturn($total);

        $this->collectionProcessor->expects($this->once())
            ->method('process')
            ->with($criteria, $this->promotionCollection)
            ->willReturnSelf();

        $this->promotionCollection->expects($this->once())
            ->method('load')
            ->willReturnSelf();

        $this->promotionCollection->expects($this->once())
            ->method('getItems')
            ->willReturn([$this->promotion]);

        $this->promotionCollection->expects($this->once())
            ->method('addPromotionGroupIds')
            ->willReturnSelf();

        $this->searchResults->expects($this->once())
            ->method('setSearchCriteria')
            ->with($criteria)
            ->willReturnSelf();
        $this->searchResults->expects($this->once())
            ->method('setTotalCount')
            ->with($total)
            ->willReturnSelf();

        $this->searchResults->expects($this->once())
            ->method('setItems')
            ->with([$this->promotion])
            ->willReturnSelf();

        $this->assertEquals(
            $this->searchResults,
            $this->testObject->getList($criteria)
        );
    }
}
