<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Revision;

use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterfaceFactory;
use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\Api\RevisionRepositoryInterface;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Buildify\Api\Data\BuilderConfigInterfaceFactory;
use Aheadworks\Buildify\Api\Data\BuilderConfigInterface;

/**
 * Class GetContent
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Revision
 */
class GetContent extends BackendAction
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
     * @var RevisionRepositoryInterface
     */
    private $revisionRepository;

    /**
     * @var EditorConfigRequestInterfaceFactory
     */
    private $editorConfigRequestFactory;

    /**
     * @var BuilderConfigInterfaceFactory
     */
    private $builderConfigInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param RequestManagementInterface $requestManagement
     * @param RevisionRepositoryInterface $revisionRepository
     * @param EditorConfigRequestInterfaceFactory $editorConfigRequestFactory
     * @param BuilderConfigInterfaceFactory $builderConfigInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        RequestManagementInterface $requestManagement,
        RevisionRepositoryInterface $revisionRepository,
        EditorConfigRequestInterfaceFactory $editorConfigRequestFactory,
        BuilderConfigInterfaceFactory $builderConfigInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->requestManagement = $requestManagement;
        $this->revisionRepository = $revisionRepository;
        $this->editorConfigRequestFactory = $editorConfigRequestFactory;
        $this->builderConfigInterfaceFactory = $builderConfigInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];

        $data = $this->getRequest()->getPostValue();
        if (is_array($data) && isset($data['revision_id'])) {
            try {
                $revisionId = $data['revision_id'];
                $revision = $this->revisionRepository->get($revisionId);

                $builderConfig = $data['builder_config'];
                $builderConfigObject = $this->builderConfigInterfaceFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $builderConfigObject,
                    $builderConfig,
                    BuilderConfigInterface::class
                );

                /** @var EditorConfigRequestInterface $editorConfigRequest */
                $editorConfigRequest = $this->editorConfigRequestFactory->create();
                $editorConfigRequest
                    ->setBuilderConfig($builderConfigObject)
                    ->setEditorConfig($revision->getEditorConfig())
                    ->setIsWithHtmlContent(true)
                    ->setIsReplaceElementIds(false);

                $processedData = $this->requestManagement->lightProcessData($editorConfigRequest);

                $result = ['error' => false, 'content' => $processedData->getEditor()];
            } catch (LocalizedException $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            } catch (\Exception $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            }
        }

        return $resultJson->setData($result);
    }
}
