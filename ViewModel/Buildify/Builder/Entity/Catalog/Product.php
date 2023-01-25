<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Catalog;

use Aheadworks\Buildify\ViewModel\Buildify\Builder\EntityLocatorInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Registry;

/**
 * Class Product
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\Catalog
 */
class Product implements EntityLocatorInterface
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
    )
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        /** @var ProductInterface $product */
        if ($product = $this->registry->registry('current_product')) {
            return $product;
        }
        return false;
    }
}