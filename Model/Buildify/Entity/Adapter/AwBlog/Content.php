<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter\AwBlog;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\EntityAdapterInterface;

/**
 * Class Content
 * @package Aheadworks\Buildify\Model\Buildify\Entity\Adapter\AwBlog
 */
class Content implements EntityAdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getEntityField($entity)
    {
        if (!$entity) {
            return null;
        }

        $entityFields = $entity->getExtensionAttributes()->getAwEntityFields();

        return isset($entityFields[EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE])
            ? $entityFields[EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE]
            : null;
    }

    /**
     * {@inheritDoc}
     */
    public function setHtml($entity, $html)
    {
        $entity->setContent($html);
        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function getHtml($entity)
    {
        return $entity->getContent();
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
        return EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function updateEntityField($entity, $newEntity)
    {
        $entity->setId($newEntity->getId());
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomTheme($entity)
    {
        return null;
    }
}
