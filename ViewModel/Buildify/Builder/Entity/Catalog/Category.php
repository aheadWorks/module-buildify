<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Catalog;

use Aheadworks\Buildify\ViewModel\Buildify\Builder\EntityLocatorInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Registry;

/**
 * Class Category
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Catalog
 */
class Category implements EntityLocatorInterface
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
        /** @var CategoryInterface $category */
        if ($category = $this->registry->registry('current_category')) {
            return $category;
        }

        return false;
    }
}
