<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Template;

use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\Api\TemplateRepositoryInterface;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\Template\DataProvider\Converter as DataProviderConverter;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterfaceFactory;

/**
 * Class Import
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Template
 */
class Import extends BackendAction
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var EditorConfigRequestInterfaceFactory
     */
    private $editorConfigRequestInterfaceFactory;

    /**
     * @var RequestManagementInterface
     */
    private $requestManagement;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var DataProviderConverter
     */
    private $dataProviderConverter;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param EditorConfigRequestInterfaceFactory $editorConfigRequestInterfaceFactory
     * @param RequestManagementInterface $requestManagement
     * @param TemplateRepositoryInterface $templateRepository
     * @param DataProviderConverter $dataProviderConverter
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        EditorConfigRequestInterfaceFactory $editorConfigRequestInterfaceFactory,
        RequestManagementInterface $requestManagement,
        TemplateRepositoryInterface $templateRepository,
        DataProviderConverter $dataProviderConverter
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->editorConfigRequestInterfaceFactory = $editorConfigRequestInterfaceFactory;
        $this->requestManagement = $requestManagement;
        $this->templateRepository = $templateRepository;
        $this->dataProviderConverter = $dataProviderConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];
        $data = $this->getRequest()->getPostValue();

        if (is_array($data) && isset($data['encoded_content'])) {
            try {
                $responseContent = [];

                /** @var editorConfigRequestInterface $editorConfigRequest */
                $encodedTemplateData = $this->editorConfigRequestInterfaceFactory->create();
                $encodedTemplateData->setEncodedData($data['encoded_content']);

                $templates = $this->requestManagement->processEncodedData($encodedTemplateData);
                foreach ($templates as $template) {
                    $this->templateRepository->save($template);
                    $responseContent[] = $this->dataProviderConverter->convert($template);
                }

                $result = ['error' => false, 'templates' => $responseContent];
            } catch (LocalizedException $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            } catch (\Exception $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            }
        }

        return $resultJson->setData($result);
    }
}