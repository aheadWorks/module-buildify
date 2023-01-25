<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Cms;

use Aheadworks\Buildify\ViewModel\Buildify\Builder\EntityLocatorInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Registry;

/**
 * Class Block
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Cms
 */
class Block implements EntityLocatorInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        /** @var BlockInterface $block */
        if ($block = $this->registry->registry('cms_block')) {
            return $block;
        }

        return false;
    }
}
