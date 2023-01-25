<?php
namespace Aheadworks\Buildify\Plugin\Model;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Request\FormData\Processor as FormDataProcessor;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class BlockRepositoryPlugin
 * @package Aheadworks\Buildify\Plugin\Model
 */
class BlockRepositoryPlugin
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
     * @param BlockRepositoryInterface $subject
     * @param BlockInterface $block
     */
    public function beforeSave($subject, $block)
    {
        if (!$this->request->getPostValue('extension_attributes') && $block->getAwEntityField()) {
            return;
        }

        $entityField = $this->formDataProcessor->getEntityField(
            $this->request,
            EntityFieldInterface::CMS_BLOCK_ENTITY_TYPE
        );

        $block->setAwEntityField($entityField);
    }
}
