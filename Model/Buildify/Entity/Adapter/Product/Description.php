<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter\Product;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\EntityAdapterInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Copier;

/**
 * Class Description
 * @package Aheadworks\Buildify\Model\Buildify\Entity\Adapter\Product
 */
class Description implements EntityAdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEntityField($entity)
    {
        $entityFields = $entity->getExtensionAttributes()->getAwEntityFields();

        return $entityFields[EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE] ?? null;
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
        return EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE;
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
        return null;
    }
}
