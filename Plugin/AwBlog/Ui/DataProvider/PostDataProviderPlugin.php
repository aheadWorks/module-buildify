<?php
namespace Aheadworks\Buildify\Plugin\AwBlog\Ui\DataProvider;

use Aheadworks\Buildify\Model\ExtensionAttributes\Processor as ExtensionAttributesProcessor;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class PostDataProviderPlugin
 * @package Aheadworks\Buildify\Plugin\AwBlog\Model\ResourceModel
 */
class PostDataProviderPlugin
{
    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * @var ExtensionAttributesProcessor
     */
    private $extensionAttributesProcessor;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param LoadHandler $loadHandler
     * @param ExtensionAttributesProcessor $extensionAttributesProcessor
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        LoadHandler $loadHandler,
        ExtensionAttributesProcessor $extensionAttributesProcessor,
        ObjectManagerInterface $objectManager
    ) {
        $this->loadHandler = $loadHandler;
        $this->extensionAttributesProcessor = $extensionAttributesProcessor;
        $this->objectManager = $objectManager;
    }

    /**
     * @param Aheadworks\Blog\Ui\DataProvider\PostDataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData($subject, $result)
    {
        $postRepository = $this->getPostRepository();
        if (!$postRepository) {
            return $result;
        }

        foreach ($result as $id => &$item) {
            $post = $postRepository->get($id);
            $attributesArray = $this->extensionAttributesProcessor->getExtensionAttributesAsArray($post);

            if (!$attributesArray) {
                continue;
            }

            if (!isset($item['extension_attributes']) || !is_array($item['extension_attributes'])) {
                $item['extension_attributes'] = [];
            }
            $item['extension_attributes']['aw_entity_fields'] = $attributesArray;
        }
        return $result;
    }

    /**
     * Retrieve PostRepository instance
     *
     * @return \Aheadworks\Blog\Model\ResourceModel\PostRepository
     */
    private function getPostRepository()
    {
        return $this->objectManager->get(\Aheadworks\Blog\Model\ResourceModel\PostRepository::class);
    }
}
