<?php
namespace Aheadworks\Buildify\Model\ResourceModel;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class EntityField
 * @package Aheadworks\Buildify\Model\ResourceModel
 */
class EntityField extends AbstractDb
{
    /**
     * Buildify entity table
     */
    public const MAIN_TABLE_NAME = 'aw_buildify_entity_field';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, EntityFieldInterface::ID);
    }

    /**
     * Get entity identifier by entity data
     *
     * @param int $entityId
     * @param string $entityType
     * @return int|false
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getIdByEntityData($entityId, $entityType)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), $this->getIdFieldName())
            ->where('entity_id = :entity_id')
            ->where('entity_type = :entity_type');

        return $connection->fetchOne($select, ['entity_id' => $entityId, 'entity_type' => $entityType]);
    }

    /**
     * Delete duplicate entity field Id to remove
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteDuplicate()
    {
        $duplicatedIds = $this->getDuplicatedIdsToRemove();

        $this->getConnection()->delete(
            $this->getMainTable(),
            ['id IN (?)' => $duplicatedIds]
        );

        return true;
    }

    /**
     * Retrieve duplicate entity field Id to remove
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getDuplicatedIdsToRemove()
    {
        $tableName = $this->getTable(self::MAIN_TABLE_NAME);
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), [EntityFieldInterface::ID])
            ->where($tableName . '.id < entity_field.id')
            ->where($tableName . '.entity_id = entity_field.entity_id')
            ->where($tableName . '.entity_type = entity_field.entity_type');
        $this->joinEntityFieldTable($select);

        return $this->getConnection()->fetchCol($select);
    }

    /**
     * Join aw_buildify_entity_field table
     * @param Select $select
     * @return void
     */
    private function joinEntityFieldTable($select)
    {
        $select->joinInner(
            ['entity_field' => $this->getTable(self::MAIN_TABLE_NAME)],
            '',
            ''
        );
    }
}
