<?php
namespace Aheadworks\Buildify\Ui\DataProvider\Product\Form\Modifier;

use Aheadworks\Buildify\Model\ExtensionAttributes\Processor as ExtensionAttributesProcessor;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class ExtensionAttributes
 * @package Aheadworks\Buildify\Ui\DataProvider\Product\Form\Modifier
 */
class ExtensionAttributes extends AbstractModifier
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ExtensionAttributesProcessor
     */
    private $extensionAttributesProcessor;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param LocatorInterface $locator
     * @param ExtensionAttributesProcessor $extensionAttributesProcessor
     */
    public function __construct(DataObjectProcessor $dataObjectProcessor, LocatorInterface $locator, ExtensionAttributesProcessor $extensionAttributesProcessor)
    {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->locator = $locator;
        $this->extensionAttributesProcessor = $extensionAttributesProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();
        $productId = $product->getId();

        $attributesArray = $this->extensionAttributesProcessor->getExtensionAttributesAsArray($product);

        if (!$attributesArray) {
            return $data;
        }

        if (!isset($data[$productId]['extension_attributes']) || !is_array($data[$productId]['extension_attributes'])) {
            $data[$productId]['extension_attributes'] = [];
        }
        $data[$productId]['extension_attributes']['aw_entity_fields'] = $attributesArray;

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}