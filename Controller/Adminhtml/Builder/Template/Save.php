<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Template;

use Aheadworks\Buildify\Api\TemplateRepositoryInterface;
use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Aheadworks\Buildify\Api\Data\TemplateInterfaceFactory;
use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\Template\DataProvider\Converter as DataProviderConverter;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action as BackendAction;
use Magento\Framework\Controller\Result\JsonFactory;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterfaceFactory;

/**
 * Class Save
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Template
 */
class Save extends BackendAction
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RequestManagementInterface
     */
    private $requestManagement;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var TemplateInterfaceFactory
     */
    private $templateFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataProviderConverter
     */
    private $dataProviderConverter;

    /**
     * @var EditorConfigRequestInterfaceFactory
     */
    private $editorConfigRequestFactory;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param RequestManagementInterface $requestManagement
     * @param TemplateRepositoryInterface $templateRepository
     * @param TemplateInterfaceFactory $templateFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataProviderConverter $dataProviderConverter
     * @param EditorConfigRequestInterfaceFactory $editorConfigRequestFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        RequestManagementInterface $requestManagement,
        TemplateRepositoryInterface $templateRepository,
        TemplateInterfaceFactory $templateFactory,
        DataObjectHelper $dataObjectHelper,
        DataProviderConverter $dataProviderConverter,
        EditorConfigRequestInterfaceFactory $editorConfigRequestFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->requestManagement = $requestManagement;
        $this->templateRepository = $templateRepository;
        $this->templateFactory = $templateFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataProviderConverter = $dataProviderConverter;
        $this->editorConfigRequestFactory = $editorConfigRequestFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];

        $data = $this->getRequest()->getPostValue();
        if (is_array($data) && isset($data['editor']) && isset($data['template'])) {
            try {
                /** @var EditorConfigRequestInterface $editorConfigRequest */
                $editorConfigRequest = $this->editorConfigRequestFactory->create();
                $editorConfigRequest
                    ->setEditorConfig($data['editor']);

                $processedData = $this->requestManagement->processData($editorConfigRequest);
                /** @var TemplateInterface $template */
                $template = $this->templateFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $template,
                    $data['template'],
                    TemplateInterface::class
                );
                $template
                    ->setEditorConfig($processedData->getEditor())
                    ->setCssStyle($processedData->getStyle());

                $this->templateRepository->save($template);

                $result = ['error' => false, 'content' => $this->dataProviderConverter->convert($template)];
            } catch (LocalizedException $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            } catch (\Exception $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            }
        }

        return $resultJson->setData($result);
    }
}
