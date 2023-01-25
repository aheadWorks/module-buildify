<?php
namespace Aheadworks\Buildify\Model\ResourceModel\Buildify\Template;

use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Aheadworks\Buildify\Model\Buildify\Template;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Template as ResourceTemplate;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Aheadworks\Buildify\Model\ResourceModel\Buildify\Template
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = TemplateInterface::ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Template::class, ResourceTemplate::class);
    }
}
