<?php
namespace Aheadworks\Buildify\Model\ResourceModel\EntityField;

use Aheadworks\Buildify\Model\EntityField;
use Aheadworks\Buildify\Model\ResourceModel\EntityField as ResourceEntityField;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as FrameworkAbstractCollection;

/**
 * Class Collection
 * @package Aheadworks\Buildify\Model\ResourceModel\EntityField
 */
class Collection extends FrameworkAbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(EntityField::class, ResourceEntityField::class);
    }
}
