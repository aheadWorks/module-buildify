<?php
namespace Aheadworks\Buildify\Model\ResourceModel\Buildify;

use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Template
 * @package Aheadworks\Buildify\Model\ResourceModel\Buildify
 */
class Template extends AbstractDb
{
    /**
     * Buildify template table
     */
    const MAIN_TABLE_NAME = 'aw_buildify_template';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE_NAME, TemplateInterface::ID);
    }
}
