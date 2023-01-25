<?php
namespace Aheadworks\Buildify\Model\Buildify;

use Aheadworks\Buildify\Model\EntityProcessor;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Revision as ResourceRevision;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Class Revision
 * @package Aheadworks\Buildify\Model\Buildify
 */
class Revision extends AbstractModel implements RevisionInterface
{
    /**
     * @var EntityProcessor
     */
    private $processor;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param EntityProcessor $processor
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EntityProcessor $processor,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->processor = $processor;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceRevision::class);
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
    public function getBfyEntityId()
    {
        return $this->getData(self::BFY_ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setBfyEntityId($id)
    {
        return $this->setData(self::BFY_ENTITY_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
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

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $this->processor->prepareDataBeforeSave($this);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function afterLoad()
    {
        $this->processor->prepareDataAfterLoad($this);
        return $this;
    }
}
