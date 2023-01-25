<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\Adapter;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;

/**
 * Interface EntityAdapterInterface
 * @package Aheadworks\Buildify\Model\Buildify\Entity\Adapter
 */
interface EntityAdapterInterface
{
    /**
     * Retrieve buildify entity from entity
     *
     * @param $entity
     * @return EntityFieldInterface
     */
    public function getEntityField($entity);

    /**
     * Update and retrieve buildify entity
     *
     * @param $entity
     * @param $newEntity
     * @return mixed
     */
    public function updateEntityField($entity, $newEntity);

    /**
     * Set processed html to entity
     *
     * @param $entity
     * @param string $html
     * @return mixed
     */
    public function setHtml($entity, $html);

    /**
     * Get processed html
     *
     * @param $entity
     * @return string
     */
    public function getHtml($entity);

    /**
     * Retrieve entity id
     *
     * @param $entity
     * @return int
     */
    public function getId($entity);

    /**
     * Retrieve entity type
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Retrieve custom theme
     *
     * @param $entity
     * @return string|null
     */
    public function getCustomTheme($entity);
}
