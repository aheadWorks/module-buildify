<?php
namespace Aheadworks\Buildify\Model\ResourceModel\Design\Config\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

/**
 * Class Collection
 * @package Aheadworks\Buildify\Model\ResourceModel\Design\Config\Grid
 */
class Collection extends SearchResult
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Retrieve themes with shop names
     *
     * @return $this
     */
    public function getStoreThemes()
    {
        $this->joinStoreTable();

        return $this->addFieldToSelect('store_id')
            ->addFieldToSelect('theme_theme_id')
            ->addFieldToFilter('main_table.store_id', ['neq' => 'NULL']);
    }

    /**
     * Join store table
     */
    private function joinStoreTable()
    {
        $this->getSelect()->joinInner(
            ['store' => $this->getTable('store')],
            'main_table.store_id = store.store_id',
            ['store_name' => 'store.name']
        );
    }
}