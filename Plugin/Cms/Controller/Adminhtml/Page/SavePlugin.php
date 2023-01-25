<?php
namespace Aheadworks\Buildify\Plugin\Cms\Controller\Adminhtml\Page;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Request\FormData\Processor as FormDataProcessor;
use \Magento\Cms\Controller\Adminhtml\Page\Save;
use Magento\Framework\App\RequestInterface;

/**
 * Class SavePlugin
 * @package Aheadworks\Buildify\Plugin\Cms\Controller\Adminhtml\Page
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
     * @param RequestInterface $request
     * @param FormDataProcessor $formDataProcessor
     */
    public function __construct(
        RequestInterface $request,
        FormDataProcessor $formDataProcessor
    ) {
        $this->request = $request;
        $this->formDataProcessor = $formDataProcessor;
    }

    /**
     * @param Save $subject
     */
    public function beforeExecute($subject)
    {
        $postValue = $this->request->getPostValue();

        if (!isset(
            $postValue['extension_attributes']['aw_entity_fields'][EntityFieldInterface::CMS_PAGE_ENTITY_TYPE]
        )) {
            return;
        }

        $entityDescField = $this->formDataProcessor->getEntityField(
            $this->request,
            EntityFieldInterface::CMS_PAGE_ENTITY_TYPE
        );

        $postValue['aw_entity_field'] = $entityDescField;
        $this->request->setPostValue($postValue);
    }
}