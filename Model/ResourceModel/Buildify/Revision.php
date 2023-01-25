<?php
namespace Aheadworks\Buildify\Model\ResourceModel\Buildify;

use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Revision
 * @package Aheadworks\Buildify\Model\ResourceModel\Buildify
 */
class Revision extends AbstractDb
{
    /**
     * Buildify template table
     */
    const MAIN_TABLE_NAME = 'aw_buildify_revision';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, RevisionInterface::ID);
    }

    /**
     * {@inheritdoc}
     */
    protected function processAfterSaves(AbstractModel $object)
    {
        parent::processAfterSaves($object);
        $this->removeOldRevisions($object);
    }

    /**
     * Remove old revisions
     *
     * @param RevisionInterface $object
     * @return $this
     * @throws \Exception
     */
    private function removeOldRevisions($object)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), [RevisionInterface::ID])
            ->order(RevisionInterface::CREATED_AT . ' DESC')
            ->where(RevisionInterface::BFY_ENTITY_ID . '=?', $object->getBfyEntityId())
            ->limit(7);

        $select = implode(
            ' AND ',
            [
                RevisionInterface::BFY_ENTITY_ID . '=' . $object->getBfyEntityId(),
                RevisionInterface::ID . ' NOT IN ' . new \Zend_Db_Expr('(SELECT * FROM (' . $select . ') as rev_ids)')
            ]
        );

        $connection = $this->transactionManager->start($this->getConnection());
        try {
            $this->objectRelationProcessor->delete(
                $this->transactionManager,
                $connection,
                $this->getMainTable(),
                $select,
                []
            );
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }

        return $this;
    }
}
