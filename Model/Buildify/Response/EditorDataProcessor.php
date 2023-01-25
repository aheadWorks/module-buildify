<?php
namespace Aheadworks\Buildify\Model\Buildify\Response;

use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface;
use Aheadworks\Buildify\Api\Data\EditorConfigResponseInterface;
use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\ContentPreparer;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\EntityAdapterInterface;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;

class EditorDataProcessor
{
    /**
     * @var EditorConfigRequestInterfaceFactory
     */
    private $editorConfigRequestFactory;

    /**
     * @var RequestManagementInterface
     */
    private $requestManagement;

    /**
     * @var ContentPreparer
     */
    private $contentPreparer;

    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param EditorConfigRequestInterfaceFactory $editorConfigRequestFactory
     * @param ObjectManagerInterface $objectManager
     * @param RequestManagementInterface $requestManagement
     * @param ContentPreparer $contentPreparer
     * @param LoadHandler $loadHandler
     * @param Json $serializer
     */
    public function __construct(
        EditorConfigRequestInterfaceFactory $editorConfigRequestFactory,
        ObjectManagerInterface $objectManager,
        RequestManagementInterface $requestManagement,
        ContentPreparer $contentPreparer,
        LoadHandler $loadHandler,
        Json $serializer
    ) {
        $this->editorConfigRequestFactory = $editorConfigRequestFactory;
        $this->objectManager = $objectManager;
        $this->requestManagement = $requestManagement;
        $this->contentPreparer = $contentPreparer;
        $this->loadHandler = $loadHandler;
        $this->serializer = $serializer;
    }

    /**
     * Process data
     *
     * @param mixed $object
     * @param EntityAdapterInterface $entityAdapter
     * @return EditorConfigResponseInterface
     * @throws \Exception
     */
    public function processData($object, EntityAdapterInterface $entityAdapter)
    {
        $entityType = $entityAdapter->getEntityType();
        $entityField = $entityAdapter->getEntityField($object);
        /** @var EditorConfigRequestInterface $editorConfigRequest */
        $editorConfigRequest = $this->editorConfigRequestFactory->create();

        try {
            $editorConfigRequest->setEditorConfig($entityField->getEditorConfig());
            $processedData = $this->requestManagement->processData($editorConfigRequest);
            $html = $this->contentPreparer->prepareContent(
                $processedData->getHtml(),
                $processedData->getStyle(),
                $entityType
            );
            $processedData->setHtml($html);
        } catch (\Exception $e) {
            $entityId = $entityAdapter->getId($object);
            if (!$entityId) {
                throw $e;
            }

            $exceptionMessage = 'The Buildify server is not available right now, but we are working on bringing it up. '
                . 'The content you were working on was saved successfully and you won\'t lose any of your work. '
                . 'Please get back later.';

            $data = [
                'html' => $entityAdapter->getHtml($object),
                'style' => $this->loadHandler->load($entityId, $entityType)->getCssStyle(),
                'editor' => $this->serializer->serialize($entityField->getEditorConfig()),
                'exception_message' => $exceptionMessage
            ];

            $processedData = $this->objectManager->create(EditorConfigResponseInterface::class, ['data' => $data]);
        }

        return $processedData;
    }
}
