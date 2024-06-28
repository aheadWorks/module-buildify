<?php
namespace Aheadworks\Buildify\Model\ExtensionAttributes;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Catalog\Api\Data\CategoryExtensionInterface;
use Magento\Catalog\Api\Data\ProductExtension;
use Aheadworks\Blog\Api\Data\PostExtension;

/**
 * Class Processor
 * @package Aheadworks\Buildify\Model\Buildify\ExtensionAttributes
 */
class Processor
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(DataObjectProcessor $dataObjectProcessor)
    {
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Modify entity fields extension attributes
     *
     * @param mixed $item
     * @return mixed
     */
    public function modifyExtensionAttributes($item)
    {
        $attributesArray = $this->getExtensionAttributesAsArray($item);

        if (!$attributesArray) {
            return $item;
        }

        if (!isset($item['extension_attributes']) || !is_array($item['extension_attributes'])) {
            $item['extension_attributes'] = [];
        }
        $item['extension_attributes']['aw_entity_fields'] = $attributesArray;

        return $item;
    }

    /**
     * Retrieve entity fields extension attributes as array
     *
     * @param mixed $entity
     * @return array
     */
    public function getExtensionAttributesAsArray($entity)
    {
        $attributesArray = [];
        $extensionAttributes = is_array($entity)
            ? isset($entity['extension_attributes']) ? $entity['extension_attributes'] : []
            : $entity->getExtensionAttributes();

        if (!$extensionAttributes) {
            return $attributesArray;
        }

        $awEntityFields = $extensionAttributes instanceof CategoryExtensionInterface
        || $extensionAttributes instanceof ProductExtension
        || $extensionAttributes instanceof PostExtension
            ? $extensionAttributes->getAwEntityFields() : null;
        if (!$awEntityFields) {
            return $attributesArray;
        }

        foreach ($awEntityFields as $awEntityField => $awEntityFieldObj) {
            $responseData = $this->dataObjectProcessor->buildOutputDataArray(
                $awEntityFieldObj,
                EntityFieldInterface::class
            );

            $attributesArray[$awEntityField] = $responseData;
        }

        return $attributesArray;
    }
}