<?php
namespace Aheadworks\Buildify\Model\ResourceModel\Buildify\Entity\ThirdParty;

use Aheadworks\Buildify\Model\ResourceModel\EntityField;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class Collection
 * @package Aheadworks\Buildify\Model\ResourceModel\Buildify\Entity\ThirdParty
 */
class Collection
{
    /**
     * @var string
     */
    private $relativeEntityIdField;

    /**
     * @var string
     */
    private $relativeEntityType;

    /**
     * @var string
     */
    private $mainTable;

    /**
     * @var array
     */
    private $additionalColumns = [];

    /**
     * @param string $relativeEntityIdField
     * @param string $relativeEntityType
     * @param string $mainTable
     * @param array $additionalColumns
     */
    public function __construct(
        $relativeEntityIdField = '',
        $relativeEntityType = '',
        $mainTable = 'main_table',
        $additionalColumns = []
    ) {
        $this->relativeEntityIdField = $relativeEntityIdField;
        $this->relativeEntityType = $relativeEntityType;
        $this->mainTable = $mainTable;
        $this->additionalColumns = array_merge($this->additionalColumns, $additionalColumns);
    }

    /**
     * Join fields before load
     *
     * @param AbstractDb $collection
     * @return AbstractDb
     */
    public function joinFieldsBeforeLoad($collection)
    {
        if (!$collection->isLoaded()) {
            $this->joinFields($collection);
        }

        return $collection;
    }

    /**
     * Join fields
     *
     * @param AbstractDb $collection
     * @return void
     */
    private function joinFields($collection)
    {
        $select = $collection->getSelect();

        $select->joinLeft(
            ['awbfy_entity' => $collection->getTable(EntityField::MAIN_TABLE_NAME)],
            'awbfy_entity.entity_id = ' . $this->mainTable . '.' . $this->relativeEntityIdField . ' AND ' .
            'awbfy_entity.entity_type = "' . $this->relativeEntityType . '"',
            $this->additionalColumns
        );

        foreach ($this->additionalColumns as $filter => $alias) {
            $collection->addFilterToMap($filter, $alias);
        }
    }
}
