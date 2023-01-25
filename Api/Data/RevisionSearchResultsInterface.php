<?php
namespace Aheadworks\Buildify\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface RevisionSearchResultsInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface RevisionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get template list
     *
     * @return \Aheadworks\Buildify\Api\Data\RevisionInterface[]
     */
    public function getItems();

    /**
     * Set template list
     *
     * @param \Aheadworks\Buildify\Api\Data\RevisionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
