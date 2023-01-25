<?php
namespace Aheadworks\Buildify\Model;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\ResourceModel\EntityField as ResourceEntityField;
use Magento\Framework\Model\AbstractModel;

/**
 * Class EntityField
 * @package Aheadworks\Buildify\Model
 */
class EntityField extends AbstractModel implements EntityFieldInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceEntityField::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * {@inheritdoc}
     */
    public function getCssStyle()
    {
        return $this->getData(self::CSS_STYLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCssStyle($cssStyle)
    {
        return $this->setData(self::CSS_STYLE, $cssStyle);
    }

    /**
     * {@inheritdoc}
     */
    public function getEditorConfig()
    {
        return $this->getData(self::EDITOR_CONFIG);
    }

    /**
     * {@inheritdoc}
     */
    public function setEditorConfig($editorConfig)
    {
        return $this->setData(self::EDITOR_CONFIG, $editorConfig);
    }
}
