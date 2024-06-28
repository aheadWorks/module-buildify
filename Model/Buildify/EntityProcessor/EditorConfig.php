<?php
namespace Aheadworks\Buildify\Model\Buildify\EntityProcessor;

use Aheadworks\Buildify\Api\Data\CommonEntityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class EditorConfig
 * @package Aheadworks\Buildify\Model\Buildify\EntityProcessor
 */
class EditorConfig
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param Json $serializer
     */
    public function __construct(
        Json $serializer
    ) {
        $this->serializer = $serializer;
    }

    /**
     * Convert editor data before save
     *
     * @param AbstractModel|CommonEntityInterface $object
     * @return AbstractModel|CommonEntityInterface
     */
    public function beforeSave($object)
    {
        if (is_array($object->getEditorConfig())) {
            $object->setEditorConfig($this->serializer->serialize($object->getEditorConfig()));
        }

        return $object;
    }

    /**
     * Convert editor data after load
     *
     * @param AbstractModel|CommonEntityInterface $object
     * @return AbstractModel|CommonEntityInterface
     */
    public function afterLoad($object)
    {
        if ($object->getEditorConfig()) {
            $editorArray = $this->serializer->unserialize($object->getEditorConfig());
            $object->setEditorConfig($editorArray);
        }

        return $object;
    }
}
