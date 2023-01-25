<?php
namespace Aheadworks\Buildify\Plugin\AwBlog\Model\ResourceModel;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\DeleteHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\SaveHandlerFactory;

/**
 * Class PostPlugin
 * @package Aheadworks\Buildify\Plugin\AwBlog\Model\ResourceModel
 */
class PostRepositoryPlugin
{
    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * @var SaveHandlerFactory
     */
    private $saveHandlerFactory;

    /**
     * @var DeleteHandler
     */
    private $deleteHandler;

    /**
     * @param SaveHandlerFactory $saveHandlerFactory
     * @param LoadHandler $loadHandler
     */
    public function __construct(
        SaveHandlerFactory $saveHandlerFactory,
        LoadHandler $loadHandler,
        DeleteHandler $deleteHandler
    ) {
        $this->saveHandlerFactory = $saveHandlerFactory;
        $this->loadHandler = $loadHandler;
        $this->deleteHandler = $deleteHandler;
    }

    /**
     * @param Aheadworks\Blog\Model\ResourceModel\PostRepository $subject
     * @param callable $proceed
     * @param PostInterface $post
     * @return mixed
     * @throws \Exception
     */
    public function aroundSave($subject, $proceed, PostInterface $post)
    {
        $awEntityFields = $post->getExtensionAttributes()->getAwEntityFields();

        if (isset($awEntityFields[EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE])) {
            $saveHandler = $this->saveHandlerFactory->create(EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE);
            $result = $saveHandler->save($post, $proceed);
        } else {
            $result = $proceed($post);
        }

        return $result;
    }

    /**
     * @param Aheadworks\Blog\Model\ResourceModel\PostRepository $subject
     * @param Aheadworks\Blog\Model\ResourceModel\PostRepository $result
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterGet($subject, $result)
    {
        $extensionAttributes = $result->getExtensionAttributes();
        $awEntityFields = $extensionAttributes->getAwEntityFields() ?: [];

        $awEntityFields[EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE] = $this->loadHandler->load(
            $result->getId(),
            EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE
        );

        $extensionAttributes->setAwEntityFields($awEntityFields);

        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param Aheadworks\Blog\Model\ResourceModel\PostRepository $subject
     * @param bool $result
     * @param Aheadworks\Blog\Api\Data\PostInterface $post
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterdeleteById($subject, $result, $postId)
    {
        $this->deleteHandler->delete($postId, EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE);

        return $result;
    }
}