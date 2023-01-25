<?php
namespace Aheadworks\Buildify\Plugin\AwBlog\Controller\Adminhtml\Post;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Request\FormData\Processor as FormDataProcessor;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Controller\Adminhtml\Post\Save;
use Aheadworks\Blog\Api\Data\PostExtensionFactory;

/**
 * Class SavePlugin
 * @package Aheadworks\Buildify\Plugin\AwBlog\Controller\Adminhtml\Post
 */
class SavePlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var FormDataProcessor
     */
    private $formDataProcessor;

    /**
     * @var PostExtensionFactory
     */
    private $postExtensionFactory;

    /**
     * @param RequestInterface $request
     * @param FormDataProcessor $formDataProcessor
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        RequestInterface $request,
        FormDataProcessor $formDataProcessor,
        ObjectManagerInterface $objectManager
    ) {
        $this->request = $request;
        $this->formDataProcessor = $formDataProcessor;
        if (class_exists(PostExtensionFactory::class)) {
            $this->postExtensionFactory = $objectManager->get(PostExtensionFactory::class);
        }
    }

    /**
     * Convert extension attributes to object
     *
     * @param Save $subject
     */
    public function beforeExecute(Save $subject)
    {
        $postValue = $this->request->getPostValue();
        if (!isset(
            $postValue['extension_attributes']['aw_entity_fields'][EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE]
        )) {
            return;
        }

        $entityDescField = $this->formDataProcessor->getEntityField(
            $this->request,
            EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE
        );

        $extensionAttributes = $postValue['extension_attributes'];
        if (!is_object($extensionAttributes)) {
            $extensionAttributes = $this->postExtensionFactory->create();
        }
        $awEntityFields[EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE] = $entityDescField;

        $extensionAttributes->setAwEntityFields($awEntityFields);
        $postValue['extension_attributes'] = $extensionAttributes;
        $this->request->setPostValue($postValue);
    }
}
