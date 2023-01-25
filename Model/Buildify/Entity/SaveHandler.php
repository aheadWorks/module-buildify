<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity;

use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Aheadworks\Buildify\Api\Data\RevisionInterfaceFactory;
use Aheadworks\Buildify\Api\RevisionRepositoryInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Repository as EntityRepository;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\EntityAdapterInterface;
use Aheadworks\Buildify\Model\Entity\Config as EntityConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface;
use Aheadworks\Buildify\Model\Config;
use Aheadworks\Buildify\Model\Buildify\Response\EditorDataProcessor;

/**
 * Class SaveHandler
 * @package Aheadworks\Buildify\Model\Buildify\Entity
 */
class SaveHandler
{
    /**
     * @var RevisionInterfaceFactory
     */
    private $revisionFactory;

    /**
     * @var EntityAdapterInterface
     */
    private $entityAdapter;

    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * @var RevisionRepositoryInterface
     */
    private $revisionRepository;

    /**
     * @var TransactionManagerInterface
     */
    private $transactionManager;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var EntityConfig
     */
    private $entityConfig;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var EditorDataProcessor
     */
    private $editorDataProcessor;

    /**
     * @param RevisionInterfaceFactory $revisionFactory
     * @param EntityAdapterInterface $entityAdapter
     * @param EntityRepository $entityRepository
     * @param RevisionRepositoryInterface $revisionRepository
     * @param TransactionManagerInterface $transactionManager
     * @param ResourceConnection $resourceConnection
     * @param EntityConfig $entityConfig
     * @param Config $config
     * @param EditorDataProcessor $editorDataProcessor
     */
    public function __construct(
        RevisionInterfaceFactory $revisionFactory,
        EntityAdapterInterface $entityAdapter,
        EntityRepository $entityRepository,
        RevisionRepositoryInterface $revisionRepository,
        TransactionManagerInterface $transactionManager,
        ResourceConnection $resourceConnection,
        EntityConfig $entityConfig,
        Config $config,
        EditorDataProcessor $editorDataProcessor
    ) {
        $this->revisionFactory = $revisionFactory;
        $this->entityAdapter = $entityAdapter;
        $this->entityRepository = $entityRepository;
        $this->revisionRepository = $revisionRepository;
        $this->transactionManager = $transactionManager;
        $this->resourceConnection = $resourceConnection;
        $this->entityConfig = $entityConfig;
        $this->config = $config;
        $this->editorDataProcessor = $editorDataProcessor;
    }

    /**
     * Save buildify builder config
     *
     * @param mixed $object
     * @param callable $proceed
     * @return mixed
     * @throws \Exception
     */
    public function save($object, $proceed)
    {
        $connection = $this->resourceConnection->getConnection();
        $entityType = $this->entityAdapter->getEntityType();
        $entityField = $this->entityAdapter->getEntityField($object);

        if (!$this->entityConfig->isEntityTypeEnable($entityType)
            || !$this->config->isPossibleToProcess()
            || $entityField->getEditorConfig() === null
        ) {
            return $proceed($object);
        }

        $processedData = $this->editorDataProcessor->processData($object, $this->entityAdapter);

        try {
            $this->transactionManager->start($connection);

            $this->entityAdapter->setHtml($object, $processedData->getHtml());
            $result = $proceed($object);
            $this->entityAdapter->updateEntityField($object, $result);

            $entityId = $this->entityAdapter->getId($object);

            $entityField
                ->setEditorConfig($processedData->getEditor())
                ->setCssStyle($processedData->getStyle());
            $entityField = $this->entityRepository->save($entityField, $entityId, $entityType);

            /** @var RevisionInterface $revision */
            $revision = $this->revisionFactory->create();
            $revision
                ->setBfyEntityId($entityField->getId())
                ->setEditorConfig($processedData->getEditor())
                ->setCssStyle($processedData->getStyle());
            $this->revisionRepository->save($revision);
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }

        if ($exceptionMessage = $processedData->getExceptionMessage()) {
            throw new LocalizedException(__($exceptionMessage));
        }

        return $result;
    }
}
