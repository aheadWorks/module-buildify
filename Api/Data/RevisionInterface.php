<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface RevisionInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface RevisionInterface extends CommonEntityInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const BFY_ENTITY_ID = 'bfy_entity_id';
    const CREATED_AT = 'created_at';
    /**#@-*/

    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get bfy entity id
     *
     * @return int
     */
    public function getBfyEntityId();

    /**
     * Set bfy entity id
     *
     * @param int $id
     * @return $this
     */
    public function setBfyEntityId($id);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}
