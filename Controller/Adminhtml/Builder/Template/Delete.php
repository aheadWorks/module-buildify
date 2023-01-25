<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Template;

use Aheadworks\Buildify\Api\TemplateRepositoryInterface;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Delete
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Template
 */
class Delete extends BackendAction
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        TemplateRepositoryInterface $templateRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->templateRepository = $templateRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];

        $data = $this->getRequest()->getPostValue();
        if (is_array($data) && isset($data['template_id'])) {
            try {
                $templateId = $data['template_id'];

                $this->templateRepository->deleteByExternalId($templateId);

                $result = ['error' => false, 'content' => ''];
            } catch (LocalizedException $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            } catch (\Exception $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            }
        }

        return $resultJson->setData($result);
    }
}
