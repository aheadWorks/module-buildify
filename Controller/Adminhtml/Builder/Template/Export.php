<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Template;

use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\Api\TemplateRepositoryInterface;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Aheadworks\Buildify\Api\Data\EditorConfigResponseInterface;
use Aheadworks\Buildify\Model\Buildify\Template\Export\FileManager;

/**
 * Class Export
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Template
 */
class Export extends BackendAction
{
    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var RequestManagementInterface
     */
    private $requestManagement;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @param Context $context
     * @param FileFactory $fileFactory
     * @param TemplateRepositoryInterface $templateRepository
     * @param RequestManagementInterface $requestManagement
     * @param FileManager $fileManager
     */
    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        TemplateRepositoryInterface $templateRepository,
        RequestManagementInterface $requestManagement,
        FileManager $fileManager
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->templateRepository = $templateRepository;
        $this->requestManagement = $requestManagement;
        $this->fileManager = $fileManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function execute()
    {
        $content = '';
        $fileName = FileManager::DEFAULT_FILE_NAME . '.' . FileManager::DEFAULT_FILE_EXTENSION;
        $params = $this->getRequest()->getParams();

        if (is_array($params) && isset($params['template_id'])) {
            $templateId = $params['template_id'];

            $template = $this->templateRepository->getByExternalId($templateId);
            $fileName = $this->fileManager->getFileName($template);

            /** @var EditorConfigResponseInterface $encodedData */
            $encodedData = $this->requestManagement->encodeTemplateData($template);
            $content = $encodedData->getEncodedContent();
        }

        return $this->fileFactory->create($fileName, $content, 'var');
    }
}