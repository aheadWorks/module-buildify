<?php
namespace Aheadworks\Buildify\Model\Product\CopyConstructor;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Copier;
use Magento\Catalog\Model\Product\CopyConstructorInterface;
use Magento\Catalog\Model\Product;

/**
 * Class EntityField
 * @package Aheadworks\Buildify\Model\Product\CopyConstructor
 */
class EntityField implements CopyConstructorInterface
{
    /**
     * @var Copier
     */
    private $copier;

    /**
     * @param Copier $copier
     */
    public function __construct(
        Copier $copier
    ) {
        $this->copier = $copier;
    }

    /**
     * {@inheritDoc}
     */
    public function build(Product $product, Product $duplicate)
    {
        if (!$product->getExtensionAttributes() || !$product->getExtensionAttributes()->getAwEntityFields()) {
            return;
        }

        $extensionAttributes = $product->getExtensionAttributes();
        $entityFields = $extensionAttributes->getAwEntityFields();
        $entityField = $entityFields[EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE] ?? null;

        if ($entityField) {
            $entityField =  $this->copier->copy($entityField);
            $entityFields[EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE] = $entityField;

            $extensionAttributes->setAwEntityFields($entityFields);
            $duplicate->setExtensionAttributes($extensionAttributes);
        }
    }
}