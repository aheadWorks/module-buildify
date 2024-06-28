<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter\Catalog;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\EntityAdapterInterface;
use Magento\Catalog\Api\Data\CategoryExtensionInterface;

/**
 * Class Page
 * @package Aheadworks\Buildify\Model\Buildify\Entity\SaveHandler\Adapter
 */
class Category implements EntityAdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEntityField($entity)
    {
        $extensionAttributes = $entity->getExtensionAttributes();
        $entityFields = $extensionAttributes instanceof CategoryExtensionInterface ?
            $extensionAttributes->getAwEntityFields() : [];

        return isset($entityFields[EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE])
            ? $entityFields[EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE]
            : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setHtml($entity, $html)
    {
        $entity->setDescription($html);
        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function getHtml($entity)
    {
        return $entity->getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function getId($entity)
    {
        return $entity->getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityType()
    {
        return EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntityField($entity, $newEntity)
    {
        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomTheme($entity)
    {
        return !$entity->getCustomUseParentSettings() ? $entity->getCustomDesign() : null;
    }
}
