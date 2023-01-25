<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Copier;

/**
 * Class Block
 * @package Aheadworks\Buildify\Model\Buildify\Entity\Adapter
 */
class Block implements EntityAdapterInterface
{
    /**
     * @var Copier
     */
    protected $copier;

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
    public function getEntityField($entity)
    {
        if (
            $entity->getBack() === 'duplicate'
            && $entity->isObjectNew()
            && $entity->getAwEntityField()
        ) {
            $currentAwEntityField = $entity->getAwEntityField();
            $newAwEntityField =  $this->copier->copy($currentAwEntityField);

            $entity->setAwEntityField($newAwEntityField);
        }

        return $entity->getAwEntityField();
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
        return EntityFieldInterface::CMS_BLOCK_ENTITY_TYPE;
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
