<?php
namespace Aheadworks\Buildify\Model\ResourceModel\Buildify\Revision;

use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Aheadworks\Buildify\Model\Buildify\Revision;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Revision as ResourceRevision;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Aheadworks\Buildify\Model\ResourceModel\Buildify\Revision
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = RevisionInterface::ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Revision::class, ResourceRevision::class);
    }
}