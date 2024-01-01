<?php
namespace TMajka\Promotion\Test\Unit\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TMajka\Promotion\Api\Data\PromotionGroupSearchResultsInterface;
use TMajka\Promotion\Api\Data\PromotionGroupSearchResultsInterfaceFactory;
use TMajka\Promotion\Model\PromotionGroup;
use TMajka\Promotion\Model\PromotionGroupFactory;
use TMajka\Promotion\Model\PromotionGroupRepository;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup as PromotionGroupResourceModel;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup\Collection;
use TMajka\Promotion\Model\ResourceModel\PromotionGroup\CollectionFactory;

/**
 * @covers PromotionGroupRepository
 */
class PromotionGroupRepositoryTest extends TestCase
{
    /**
     * Mock promotionGroupModel
     *
     * @var PromotionGroup|MockObject
     */
    private $promotionGroup;

    /**
     * Mock promotionGroupFactory
     *
     * @var PromotionGroupFactory|MockObject
     */
    private $promotionGroupFactory;

    /**
     * Mock promotionGroupResourceModel
     *
     * @var PromotionGroupResourceModel|MockObject
     */
    private $promotionGroupResourceModel;

    /**
     * Mock promotionGroupCollection
     *
     * @var Collection|MockObject
     */
    private $promotionGroupCollection;

    /**
     * Mock promotionGroupCollectionFactory
     *
     * @var CollectionFactory|MockObject
     */
    private $promotionGroupCollectionFactory;

    /**
     * Mock collectionProcessor
     *
     * @var CollectionProcessorInterface|MockObject
     */
    private $collectionProcessor;

    /**
     * Mock searchResultsFactoryInstance
     *
     * @var PromotionGroupSearchResultsInterface|MockObject
     */
    private $searchResults;

    /**
     * Mock searchResultsFactory
     *
     * @var PromotionGroupSearchResultsInterfaceFactory|MockObject
     */
    private $searchResultsFactory;

    /**
     * Object to test
     *
     * @var PromotionGroupRepository
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->promotionGroup = $this->createMock(PromotionGroup::class);
        $this->promotionGroupFactory = $this->createMock(PromotionGroupFactory::class);
        $this->promotionGroupFactory->method('create')->willReturn($this->promotionGroup);
        $this->promotionGroupResourceModel = $this->createMock(PromotionGroupResourceModel::class);
        $this->promotionGroupCollection = $this->createMock(Collection::class);
        $this->promotionGroupCollectionFactory = $this->createMock(CollectionFactory::class);
        $this->promotionGroupCollectionFactory->method('create')->willReturn($this->promotionGroupCollection);
        $this->collectionProcessor = $this->createMock(CollectionProcessorInterface::class);
        $this->searchResults = $this->createMock(PromotionGroupSearchResultsInterface::class);
        $this->searchResultsFactory = $this->createMock(PromotionGroupSearchResultsInterfaceFactory::class);
        $this->searchResultsFactory->method('create')->willReturn($this->searchResults);
        $this->testObject = new PromotionGroupRepository(
            $this->promotionGroupFactory,
            $this->promotionGroupResourceModel,
            $this->promotionGroupCollectionFactory,
            $this->collectionProcessor,
            $this->searchResultsFactory,
        );
    }

    public function testSave(): void
    {
        $this->promotionGroup->expects($this->exactly(2))->method('getId')->willReturnOnConsecutiveCalls(null, 7);

        $this->promotionGroupResourceModel->expects($this->once())
            ->method('save')
            ->with($this->promotionGroup)
            ->willReturnSelf();

        $this->assertEquals(7, $this->testObject->save($this->promotionGroup));
    }

    public function testOnlyAddSaveException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage("You cannot edit promotions group. You can only add a new one");

        $this->promotionGroup->expects($this->once())->method('getId')->willReturn(1);

        $this->testObject->save($this->promotionGroup);
    }

    public function testSaveMainException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage('Unable to save the object to the database. Please check the data and try again.');

        $this->promotionGroup->expects($this->once())->method('getId')->willReturn(null);

        $this->promotionGroupResourceModel
            ->expects($this->once())
            ->method('save')
            ->with($this->promotionGroup)->willThrowException(new \Exception());

        $this->testObject->save($this->promotionGroup);
    }

    public function testGetById(): void
    {
        $promotionGroupId = '123';

        $this->promotionGroup->expects($this->once())
            ->method('getId')
            ->willReturn($promotionGroupId);
        $this->promotionGroupResourceModel->expects($this->once())
            ->method('load')
            ->with($this->promotionGroup, $promotionGroupId)
            ->willReturn($this->promotionGroup);

        $this->assertEquals($this->promotionGroup, $this->testObject->getById($promotionGroupId));
    }

    public function testGetByIdException(): void
    {
        $this->expectException('Magento\Framework\Exception\NoSuchEntityException');
        $this->expectExceptionMessage("The promotion group with ID: 123 doesn't exist.");
        $promotionGroupId = '123';

        $this->promotionGroup->expects($this->once())
            ->method('getId')
            ->willReturn(false);
        $this->promotionGroupResourceModel->expects($this->once())
            ->method('load')
            ->with($this->promotionGroup, $promotionGroupId)
            ->willReturn($this->promotionGroup);
        $this->testObject->getById($promotionGroupId);
    }

    public function testDeleteById(): void
    {
        $promotionGroupId = '123';

        $this->promotionGroup->expects($this->once())
            ->method('getId')
            ->willReturn($promotionGroupId);

        $this->promotionGroupResourceModel->expects($this->once())
            ->method('load')
            ->with($this->promotionGroup, $promotionGroupId)
            ->willReturn($this->promotionGroup);

        $this->promotionGroupResourceModel->expects($this->once())
            ->method('delete')
            ->with($this->promotionGroup);
        $this->assertTrue($this->testObject->deleteById($promotionGroupId));
    }

    public function testUnableDeletePromotionGroup(): void
    {
        $promotionGroupId = '7';
        $this->expectException('Magento\Framework\Exception\CouldNotDeleteException');
        $this->expectExceptionMessage('The promotion group with ID: 7 couldn\'t be removed.');
        $this->promotionGroup->expects($this->exactly(2))->method('getId')->willReturn($promotionGroupId);
        $this->promotionGroupResourceModel
            ->expects($this->once())
            ->method('delete')
            ->with($this->promotionGroup)->willThrowException(new \Exception());

        $this->testObject->deleteById($promotionGroupId);
    }

    public function testGetList(): void
    {
        $total = 10;

        /** @var SearchCriteriaInterface $criteria */
        $criteria = $this->getMockBuilder(SearchCriteriaInterface::class)
            ->getMock();

        $this->promotionGroupCollection->addItem($this->promotionGroup);
        $this->promotionGroupCollection->expects($this->once())
            ->method('getSize')
            ->willReturn($total);

        $this->collectionProcessor->expects($this->once())
            ->method('process')
            ->with($criteria, $this->promotionGroupCollection)
            ->willReturnSelf();

        $this->promotionGroupCollection->expects($this->once())
            ->method('load')
            ->willReturnSelf();

        $this->promotionGroupCollection->expects($this->once())
            ->method('getItems')
            ->willReturn([$this->promotionGroup]);

        $this->promotionGroupCollection->expects($this->once())
            ->method('addPromotionIds')
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
            ->with([$this->promotionGroup])
            ->willReturnSelf();

        $this->assertEquals(
            $this->searchResults,
            $this->testObject->getList($criteria)
        );
    }

}
