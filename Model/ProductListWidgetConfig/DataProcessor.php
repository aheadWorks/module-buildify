<?php
namespace Aheadworks\Buildify\Model\ProductListWidgetConfig;

use Aheadworks\Buildify\Api\ProductListWidgetConfigInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Rule\Model\Condition\Combine;
use Magento\Widget\Helper\Conditions;

/**
 * Class DataProcessor
 * @package Aheadworks\Buildify\Model\ProductListWidgetConfig
 */
class DataProcessor
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var Conditions
     */
    private $conditionsHelper;

    /**
     * @var Rule
     */
    private $rule;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param Conditions $conditionsHelper
     * @param Rule $rule
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        Conditions $conditionsHelper,
        Rule $rule
    ) {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->conditionsHelper = $conditionsHelper;
        $this->rule = $rule;
    }

    /**
     * Retrieve search criteria
     *
     * @param $usePager
     * @param $productsPerPage
     * @param $productCount
     * @param $currentPage
     * @param $storeId
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function prepareSearchCriteriaBuilder($usePager, $productsPerPage, $productCount, $currentPage, $storeId)
    {
        $pageSize = $this->getPageSize($usePager, $productsPerPage, $productCount);
        if ($storeId !== null) {
            $this->searchCriteriaBuilder->addFilter('store_id', $storeId);
        }

        $sortOrderByCreatedAt = $this->sortOrderBuilder
            ->setField('created_at')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();

        $this->searchCriteriaBuilder->addSortOrder($sortOrderByCreatedAt);
        $this->searchCriteriaBuilder->setPageSize($pageSize);
        $this->searchCriteriaBuilder->setCurrentPage($this->getCurrentPage($currentPage));

        return $this->searchCriteriaBuilder->create();
    }

    /**
     * Retrieve conditions
     *
     * @return Combine
     */
    public function prepareConditions($conditions)
    {
        $conditions = $this->conditionsHelper->decode($conditions);

        foreach ($conditions as $key => $condition) {
            if (!empty($condition['attribute'])) {
                if (in_array($condition['attribute'], ['special_from_date', 'special_to_date'])) {
                    $conditions[$key]['value'] = date('Y-m-d H:i:s', strtotime($condition['value']));
                }
            }
        }

        $this->rule->loadPost(['conditions' => $conditions]);
        return $this->rule->getConditions();
    }

    /**
     * Retrieve page size
     *
     * @param bool $usePager
     * @param int|null $productsPerPage
     * @param int|null $productCount
     * @return int
     */
    private function getPageSize($usePager, $productsPerPage, $productCount)
    {
        return $usePager ? $this->getProductsPerPage($productsPerPage) : $this->getProductCount($productCount);
    }

    /**
     * Retrieve products per page
     *
     * @param int|null $productsPerPage
     * @return int
     */
    private function getProductsPerPage($productsPerPage)
    {
        return $productsPerPage ?? ProductListWidgetConfigInterface::DEFAULT_PRODUCTS_PER_PAGE;
    }

    /**
     * Retrieve product count
     *
     * @param int|null $productCount
     * @return int
     */
    private function getProductCount($productCount)
    {
        return $productCount ?? ProductListWidgetConfigInterface::DEFAULT_PRODUCTS_COUNT;
    }

    /**
     * Retrieve current page
     *
     * @param int|null $currentPage
     * @return int
     */
    private function getCurrentPage($currentPage)
    {
        return $currentPage ?? ProductListWidgetConfigInterface::DEFAULT_CURRENT_PAGE;
    }
}