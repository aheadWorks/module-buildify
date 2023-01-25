<?php
namespace Aheadworks\Buildify\Plugin\AwBlog\Controller\Adminhtml\Post;

use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Controller\Adminhtml\Post\Validate;
use Aheadworks\Buildify\Api\Data\EntityFieldInterface;

/**
 * Class ValidatePlugin
 * @package Aheadworks\Buildify\Plugin\AwBlog\Controller\Adminhtml\Post
 */
class ValidatePlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Remove fake buildify default content
     *
     * @param Validate $subject
     */
    public function beforeExecute(Validate $subject)
    {
        $postValue = $this->request->getPostValue();
        if (!isset(
            $postValue['extension_attributes']['aw_entity_fields'][EntityFieldInterface::AW_BLOG_ENTITY_CONTENT_TYPE]
        )) {
            $postValue['content'] = str_replace('aw_bfy_fake_value', '', $postValue['content']);
        }

        $this->request->setPostValue($postValue);
    }
}