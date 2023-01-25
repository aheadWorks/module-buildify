<?php
namespace Aheadworks\Buildify\Model\Service;

use Aheadworks\Buildify\Api\ProductListWidgetConfigInterface;
use Aheadworks\Buildify\Model\ProductListWidgetConfig\DataProcessor;
use Aheadworks\Buildify\Model\ProductListWidgetConfig\ProductList;

/**
 * Class ProductListManagement
 * @package Aheadworks\Buildify\Model\Service
 */
class ProductListWidgetConfigService implements ProductListWidgetConfigInterface
{
    /**
     * @var DataProcessor
     */
    private $dataProcessor;

    /**
     * @var ProductList
     */
    private $productList;

    /**
     * @param DataProcessor $dataProcessor
     * @param ProductList $productList
     */
    public function __construct(
        DataProcessor $dataProcessor,
        ProductList $productList
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->productList = $productList;
    }

    /**
     * {@inheritDoc}
     */
    public function getProductListByWidgetConfig(
        $conditionsEncoded,
        $usePager,
        $productsPerPage = null,
        $currentPage = null,
        $productCount = null,
        $storeId = null
    ) {
        $searchCriteria = $this->dataProcessor->prepareSearchCriteriaBuilder(
            $usePager,
            $productsPerPage,
            $productCount,
            $currentPage,
            $storeId
        );
        $conditions = $this->dataProcessor->prepareConditions($conditionsEncoded);

        return $this->productList->getList($searchCriteria, $conditions);
    }
}