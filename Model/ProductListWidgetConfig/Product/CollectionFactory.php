<?php
namespace Aheadworks\Buildify\Model\ProductListWidgetConfig\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Rule\Model\Condition\Combine;
use Magento\Rule\Model\Condition\Sql\Builder;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

/**
 * Class CollectionFactory
 * @package Aheadworks\Buildify\Model\ProductListWidgetConfig\Product
 */
class CollectionFactory
{
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var Builder
     */
    private $sqlBuilder;

    /**
     * @var Visibility
     */
    private $catalogProductVisibility;

    /**
     * @var Config
     */
    private $catalogConfig;

    /**
     * @param ProductCollectionFactory $productCollectionFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param Builder $sqlBuilder
     * @param Visibility $catalogProductVisibility
     * @param Config $catalogConfig
     */
    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        Builder $sqlBuilder,
        Visibility $catalogProductVisibility,
        Config $catalogConfig
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->sqlBuilder = $sqlBuilder;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->catalogConfig = $catalogConfig;
    }

    /**
     * Retrieve product collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Combine $conditions
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function create(SearchCriteriaInterface $searchCriteria, Combine $conditions)
    {
        /** @var $collection Collection */
        $collection = $this->productCollectionFactory->create();
        $this->prepare($collection);

        $this->extensionAttributesJoinProcessor->process($collection, ProductInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        $collection->distinct(true);

        return $collection;
    }

    /**
     * Add all attributes and apply pricing logic to products collection
     * to get correct values in different products lists.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return void
     */
    private function prepare(Collection $collection)
    {
        $collection
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->setVisibility($this->catalogProductVisibility->getVisibleInSearchIds())
        ;
    }
}