<?php
namespace Aheadworks\Buildify\Api;

/**
 * Interface ProductListRepositoryInterface
 * @package Aheadworks\Buildify\Api
 */
interface ProductListWidgetConfigInterface
{
    /**
     * Default value for products count that will be shown
     */
    public const DEFAULT_PRODUCTS_COUNT = 10;

    /**
     * Default value for products count that will be shown
     */
    public const DEFAULT_CURRENT_PAGE = 1;

    /**
     * Default value for products per page
     */
    public const DEFAULT_PRODUCTS_PER_PAGE = 5;

    /**
     * @param string $conditionsEncoded
     * @param bool $usePager
     * @param int|null $productsPerPage
     * @param int $currentPage
     * @param int|null $productCount
     * @param int|null $storeId
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getProductListByWidgetConfig(
        $conditionsEncoded,
        $usePager,
        $productsPerPage = null,
        $currentPage = null,
        $productCount = null,
        $storeId = null
    );
}