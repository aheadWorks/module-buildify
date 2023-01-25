<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\AwBlog;

use Aheadworks\Buildify\ViewModel\Buildify\Builder\EntityLocatorInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Post
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Entity\AwBlog
 */
class Post implements EntityLocatorInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param RequestInterface $request
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        RequestInterface $request,
        ObjectManagerInterface $objectManager
    )
    {
        $this->request = $request;
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        $postRepository = $this->getPostRepository();
        if (!$postRepository) {
            return false;
        }

        $postId = (int)$this->request->getParam('id');
        if (!$postId) {
            return false;
        }

        /** @var PostInterface $product */
        if ($post = $postRepository->get($postId)) {
            return $post;
        }

        return false;
    }

    /**
     * Retrieve PostRepository instance
     *
     * @return \Aheadworks\Blog\Model\ResourceModel\PostRepository|null
     */
    private function getPostRepository()
    {
        try {
            $object = $this->objectManager->get(\Aheadworks\Blog\Model\ResourceModel\PostRepository::class);
        } catch (\Exception $e) {
            $object = null;
        }
        return $object;
    }
}

