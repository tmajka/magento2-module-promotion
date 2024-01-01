<?php declare(strict_types=1);

namespace TMajka\Promotion\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use TMajka\Promotion\Api\PromotionGroupRelationRepositoryInterface;
use TMajka\Promotion\Api\PromotionGroupRepositoryInterface;
use TMajka\Promotion\Api\PromotionRepositoryInterface;
use TMajka\Promotion\Model\Promotion;
use TMajka\Promotion\Model\PromotionFactory;
use TMajka\Promotion\Model\PromotionGroup;
use TMajka\Promotion\Model\PromotionGroupFactory;
use TMajka\Promotion\Model\PromotionGroupRelation;
use TMajka\Promotion\Model\PromotionGroupRelationFactory;

class PopulatePromotionDemoData
{

    private $promotionGroups =  [
        'dla kobiet' => [
            'różowa dama',
            'Après-ski',
        ],
        'letnie' => [
            'dzień ojca',
            'kochana mamo - dzień matki',
        ],
        'po wakacjach' => [
            'black weeks',
            'cyber monday',
            'dzień nauczyciela',
            'szkolna wyprawka'
        ],
        'stałe' => [
            'dla seniora',
            'różowa dama',
            'gratis to uczciwa cena',
        ],
        'wiosenne' => [
            'cenowe roztopy',
            'dzień babci i dziadka',
            'idzie wiosna',
            'majóweczka',
        ],
        'zimowe' => [
            'Après-ski',
            'karnawałowe szaleństwo',
            'pierwszy śnieg',
        ],
    ];
    /**
     * @return string[]
     */
    protected function getPromotions(): array
    {
        return [
            'Après-ski',
            'black weeks',
            'cenowe roztopy',
            'cyber monday',
            'dla seniora',
            'dzień babci i dziadka',
            'dzień nauczyciela',
            'dzień ojca',
            'gratis to uczciwa cena',
            'idzie wiosna',
            'karnawałowe szaleństwo',
            'kochana mamo - dzień matki',
            'majóweczka',
            'pierwszy śnieg',
            'różowa dama',
            'szkolna wyprawka',
        ];
    }

    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private PromotionFactory $promotionFactory,
        private PromotionRepositoryInterface $promotionRepository,
        private PromotionGroupFactory $promotionGroupFactory,
        private PromotionGroupRepositoryInterface $promotionGroupRepository,
        private PromotionGroupRelationFactory $relationFactory,
        private PromotionGroupRelationRepositoryInterface $relationRepository,
    ) {}

    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $promotionIds = [];
        $promotionGroupIds = [];

        foreach ($this->getPromotions() as $promotionName) {

            /** @var Promotion $promotion */
            $promotion = $this->promotionFactory->create();
            $promotion->setName($promotionName);
            $promotionIds[$promotionName] = $this->promotionRepository->save($promotion);
        }

        foreach ($this->promotionGroups as $groupName => $promotions) {

            /** @var PromotionGroup $promotionGroup */
            $promotionGroup = $this->promotionGroupFactory->create();
            $promotionGroup->setName($groupName);
            $promotionGroupIds[$groupName] = $this->promotionGroupRepository->save($promotionGroup);
        }

        foreach ($this->promotionGroups as $groupName => $promotions) {
            foreach ($promotions as $promotionName) {
                /** @var PromotionGroupRelation $relation */
                $relation = $this->relationFactory->create();
                $relation->setPromotionGroupId($promotionGroupIds[$groupName]);
                $relation->setPromotionId($promotionIds[$promotionName]);
                $this->relationRepository->save($relation);
            }
        }

        $this->moduleDataSetup->endSetup();
    }
}
