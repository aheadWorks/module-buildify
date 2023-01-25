<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface EntityFieldInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface EntityFieldInterface extends CommonEntityInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const ID = 'id';
    public const ENTITY_ID = 'entity_id';
    public const ENTITY_TYPE = 'entity_type';
    /**#@-*/

    /**
     * Buildify entity types
     */
    public const CMS_PAGE_ENTITY_TYPE = 'cms_page';
    public const CMS_BLOCK_ENTITY_TYPE = 'cms_block';
    public const CATALOG_CATEGORY_ENTITY_TYPE = 'catalog_category';
    public const CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE = 'product_description';
    public const AW_BLOG_ENTITY_CONTENT_TYPE = 'aw_blog_post_content';
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
     * Get entity id
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get entity type
     *
     * @return int
     */
    public function getEntityType();

    /**
     * Set entity type
     *
     * @param int $entityType
     * @return $this
     */
    public function setEntityType($entityType);
}
