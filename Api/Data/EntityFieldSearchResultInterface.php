<?php
namespace Aheadworks\Buildify\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface EntityFieldSearchResultInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface EntityFieldSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get entity field list
     *
     * @return \Aheadworks\Buildify\Api\Data\EntityFieldInterface[]
     */
    public function getItems();

    /**
     * Set entity field list
     *
     * @param \Aheadworks\Buildify\Api\Data\EntityFieldInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
