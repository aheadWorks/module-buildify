<?php
namespace Aheadworks\Buildify\Plugin\Model\Category;

use Aheadworks\Buildify\Model\ExtensionAttributes\Processor as ExtensionAttributesProcessor;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class DataProviderPlugin
 * @package Aheadworks\Buildify\Plugin\Model\Category
 */
class DataProviderPlugin
{
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var ExtensionAttributesProcessor
     */
    private $extensionAttributesProcessor;

    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param ExtensionAttributesProcessor $extensionAttributesProcessor
     */
    public function __construct(
        DataObjectProcessor $dataObjectProcessor,
        ExtensionAttributesProcessor $extensionAttributesProcessor
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->extensionAttributesProcessor = $extensionAttributesProcessor;
    }

    /**
     * @param \Magento\Catalog\Model\Category\DataProvider $subject
     * @param $result
     * @return array
     */
    public function afterGetData($subject, $result)
    {
        foreach ($result as &$category) {
            $category = $this->extensionAttributesProcessor->modifyExtensionAttributes($category);
        }

        return $result;
    }
}