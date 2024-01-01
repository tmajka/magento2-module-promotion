<?php
namespace TMajka\Promotion\Test\Unit\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TMajka\Promotion\Model\PromotionGroupRelation;
use TMajka\Promotion\Model\PromotionGroupRelationFactory;
use TMajka\Promotion\Model\PromotionGroupRelationRepository;
use TMajka\Promotion\Model\PromotionGroupRepository;
use TMajka\Promotion\Model\PromotionRepository;
use \TMajka\Promotion\Model\ResourceModel\PromotionGroupRelation as PromotionGroupRelationResourceModel;

/**
 * @covers \TMajka\Promotion\Model\PromotionGroupRelationRepository
 */
class PromotionGroupRelationRepositoryTest extends TestCase
{
    /**
     * Mock relationResourceModel
     *
     * @var PromotionGroupRelationResourceModel|MockObject
     */
    private $relationResourceModel;

    /**
     * Mock promotionGroupRepository
     *
     * @var PromotionGroupRepository|MockObject
     */
    private $promotionGroupRepository;

    /**
     * Mock promotionRepository
     *
     * @var PromotionRepository|MockObject
     */
    private $promotionRepository;

    /**
     * Mock relation model
     *
     * @var PromotionGroupRelation|MockObject
     */
    private $relation;

    /**
     * Mock relationFactory
     *
     * @var PromotionGroupRelationFactory|MockObject
     */
    private $relationFactory;

    /**
     * Object to test
     *
     * @var PromotionGroupRelationRepository
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->relationResourceModel = $this->createMock(PromotionGroupRelationResourceModel::class);
        $this->promotionGroupRepository = $this->createMock(PromotionGroupRepository::class);
        $this->promotionRepository = $this->createMock(PromotionRepository::class);
        $this->relation = $this->createMock(PromotionGroupRelation::class);
        $this->relationFactory = $this->createMock(PromotionGroupRelationFactory::class);
        $this->relationFactory->method('create')->willReturn($this->relation);
        $this->testObject = new PromotionGroupRelationRepository(
                $this->relationResourceModel,
                $this->promotionGroupRepository,
                $this->promotionRepository,
                $this->relationFactory
        );
    }

    public function testOnlyAddSaveException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage("You cannot edit assign promotion to promotion groups. You can only add a new one.");

        $this->relation->expects($this->once())->method('getId')->willReturn(1);

        $this->testObject->save($this->relation);
    }

    public function testGroupPromotionNoExistSaveException(): void
    {
        $this->expectException('Magento\Framework\Exception\NoSuchEntityException');
        $this->expectExceptionMessage("The promotion group with ID: 7 doesn't exist.");

        $this->relation->expects($this->once())->method('getId')->willReturn(null);
        $this->relation->expects($this->once())->method('getPromotionGroupId')->willReturn(7);

        $this->promotionGroupRepository->expects($this->once())->method('getById')
            ->willThrowException(new NoSuchEntityException(__("The promotion group with ID: 7 doesn't exist.")));

        $this->testObject->save($this->relation);
    }

    public function testPromotionNoExistSaveException(): void
    {
        $this->expectException('Magento\Framework\Exception\NoSuchEntityException');
        $this->expectExceptionMessage("The promotion group with ID: 7 doesn't exist.");

        $this->relation->expects($this->once())->method('getId')->willReturn(null);
        $this->relation->expects($this->once())->method('getPromotionGroupId')->willReturn(7);
        $this->relation->expects($this->once())->method('getPromotionId')->willReturn(10);

        $this->promotionGroupRepository->expects($this->once())->method('getById');
        $this->promotionRepository->expects($this->once())->method('getById')
            ->willThrowException(new NoSuchEntityException(__("The promotion group with ID: 7 doesn't exist.")));


        $this->testObject->save($this->relation);
    }

    public function testSuchAssignmentExistsSaveException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage("Such assignment of promotions to promotion groups already exists.");

        $this->relation->expects($this->once())->method('getId')->willReturn(null);
        $this->relation->expects($this->exactly(2))->method('getPromotionGroupId')->willReturn(7);
        $this->relation->expects($this->exactly(2))->method('getPromotionId')->willReturn(10);

        $this->promotionGroupRepository->expects($this->once())->method('getById');
        $this->promotionRepository->expects($this->once())->method('getById');
        $this->relationResourceModel->expects($this->once())->method('getRelationIdByGroupIdAndPromotionId')->willReturn(12);

        $this->testObject->save($this->relation);
    }

    public function testSaveMainException(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage('Unable to save the object to the database. Please check the data and try again.');

        $this->relation->expects($this->once())->method('getId')->willReturn(null);
        $this->relation->expects($this->exactly(2))->method('getPromotionGroupId')->willReturn(7);
        $this->relation->expects($this->exactly(2))->method('getPromotionId')->willReturn(10);

        $this->promotionGroupRepository->expects($this->once())->method('getById');
        $this->promotionRepository->expects($this->once())->method('getById');
        $this->relationResourceModel->expects($this->once())->method('getRelationIdByGroupIdAndPromotionId')->willReturn(null);


        $this->relationResourceModel
            ->expects($this->once())
            ->method('save')
            ->with($this->relation)->willThrowException(new \Exception());

        $this->testObject->save($this->relation);
    }

    public function testSave(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotSaveException');
        $this->expectExceptionMessage('Unable to save the object to the database. Please check the data and try again.');

        $this->relation->expects($this->once())->method('getId')->willReturn(null);
        $this->relation->expects($this->exactly(2))->method('getPromotionGroupId')->willReturn(7);
        $this->relation->expects($this->exactly(2))->method('getPromotionId')->willReturn(10);

        $this->promotionGroupRepository->expects($this->once())->method('getById');
        $this->promotionRepository->expects($this->once())->method('getById');
        $this->relationResourceModel->expects($this->once())->method('getRelationIdByGroupIdAndPromotionId')->willReturn(null);


        $this->relationResourceModel
            ->expects($this->once())
            ->method('save')
            ->with($this->relation)->willThrowException(new \Exception());

       ;
        $this->assertTrue($this->testObject->save($this->relation));
    }

    public function testNoExistsRelationDeleteByIds(): void
    {
        $this->expectException('Magento\Framework\Exception\NoSuchEntityException');
        $this->expectExceptionMessage('Such assignment of promotions to promotion groups already not exists.');
        $this->relationResourceModel
            ->expects($this->once())
            ->method('getRelationIdByGroupIdAndPromotionId')
            ->willReturn(null);

        $this->testObject->deleteByIds(1,1);
    }

    public function testUnableDeleteByIds(): void
    {
        $this->expectException('Magento\Framework\Exception\CouldNotDeleteException');
        $this->expectExceptionMessage('The relation couldn\'t be removed.');

        $this->relationResourceModel
            ->expects($this->once())
            ->method('getRelationIdByGroupIdAndPromotionId')
            ->with(1, 1)
            ->willReturn(1);

        $this->relationResourceModel
            ->expects($this->once())
            ->method('load')
            ->with($this->relation, 1);

        $this->relationResourceModel
            ->expects($this->once())
            ->method('delete')
            ->with($this->relation)->willThrowException(new \Exception());

        $this->assertTrue($this->testObject->deleteByIds(1,1));

        $this->testObject->deleteByIds(1,1);
    }

    public function testDeleteByIds()
    {
        $this->relationResourceModel
            ->expects($this->once())
            ->method('getRelationIdByGroupIdAndPromotionId')
            ->with(1, 1)
            ->willReturn(1);

        $this->relationResourceModel
            ->expects($this->once())
            ->method('load')
            ->with($this->relation, 1);

        $this->relationResourceModel
            ->expects($this->once())
            ->method('delete');

        $this->assertTrue($this->testObject->deleteByIds(1,1));
    }
}
