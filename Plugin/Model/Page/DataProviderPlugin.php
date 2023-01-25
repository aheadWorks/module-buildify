<?php
namespace Aheadworks\Buildify\Plugin\Model\Page;

use Aheadworks\Buildify\Model\ExtensionAttributes\Processor as ExtensionAttributesProcessor;
use Magento\Cms\Model\Page\DataProvider;

/**
 * Class DataProviderPlugin
 * @package Aheadworks\Buildify\Plugin\Model\Page
 */
class DataProviderPlugin
{
    /**
     * @var ExtensionAttributesProcessor
     */
    private $extensionAttributesProcessor;

    /**
     * @param ExtensionAttributesProcessor $extensionAttributesProcessor
     */
    public function __construct(
        ExtensionAttributesProcessor $extensionAttributesProcessor
    ) {
        $this->extensionAttributesProcessor = $extensionAttributesProcessor;
    }

    /**
     * @param DataProvider $subject
     * @param $result
     * @return array
     */
    public function afterGetData($subject, $result)
    {
        foreach ($result as &$page) {
            $page = $this->extensionAttributesProcessor->modifyExtensionAttributes($page);
        }

        return $result;
    }
}