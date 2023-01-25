<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface TemplateInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface TemplateInterface extends CommonEntityInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const EXTERNAL_ID = 'external_id';
    const TITLE = 'title';
    const SOURCE = 'source';
    const TYPE = 'type';
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
     * Set external id
     *
     * @param int $id
     * @return $this
     */
    public function setExternalId($id);

    /**
     * Get id
     *
     * @return int
     */
    public function getExternalId();

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get source
     *
     * @return string
     */
    public function getSource();

    /**
     * Set source
     *
     * @param string $source
     * @return $this
     */
    public function setSource($source);

    /**
     * Get type
     *
     * @return string
     */
    public function getType();

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

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
