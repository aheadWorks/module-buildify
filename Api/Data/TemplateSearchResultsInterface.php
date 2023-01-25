<?php
namespace Aheadworks\Buildify\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface TemplateSearchResultsInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface TemplateSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get template list
     *
     * @return \Aheadworks\Buildify\Api\Data\TemplateInterface[]
     */
    public function getItems();

    /**
     * Set template list
     *
     * @param \Aheadworks\Buildify\Api\Data\TemplateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
