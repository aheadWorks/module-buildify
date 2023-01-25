<?php
namespace Aheadworks\Buildify\Model\Buildify;

use Aheadworks\Buildify\Api\RevisionRepositoryInterface;
use Aheadworks\Buildify\Api\Data\RevisionInterfaceFactory;
use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Aheadworks\Buildify\Api\Data\RevisionSearchResultsInterface;
use Aheadworks\Buildify\Api\Data\RevisionSearchResultsInterfaceFactory;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Revision as RevisionResourceModel;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Revision\CollectionFactory as RevisionCollectionFactory;
use Aheadworks\Buildify\Model\Buildify\Revision;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class RevisionRepository
 * @package Aheadworks\Buildify\Model\Buildify
 */
class RevisionRepository implements RevisionRepositoryInterface
{
    /**
     * @var RevisionResourceModel
     */
    private $resource;

    /**
     * @var RevisionInterfaceFactory
     */
    private $revisionInterfaceFactory;

    /**
     * @var RevisionCollectionFactory
     */
    private $revisionCollectionFactory;

    /**
     * @var RevisionSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var array
     */
    private $registry = [];

    /**
     * @param RevisionResourceModel $resource
     * @param RevisionInterfaceFactory $revisionInterfaceFactory
     * @param RevisionCollectionFactory $revisionCollectionFactory
     * @param RevisionSearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        RevisionResourceModel $resource,
        RevisionInterfaceFactory $revisionInterfaceFactory,
        RevisionCollectionFactory $revisionCollectionFactory,
        RevisionSearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->revisionInterfaceFactory = $revisionInterfaceFactory;
        $this->revisionCollectionFactory = $revisionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function save(RevisionInterface $revision)
    {
        try {
            $this->resource->save($revision);
            $this->registry[$revision->getId()] = $revision;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $revision;
    }

    /**
     * {@inheritdoc}
     */
    public function get($revisionId)
    {
        if (!isset($this->registry[$revisionId])) {
            /** @var RevisionInterface $revision */
            $revision = $this->revisionInterfaceFactory->create();
            $this->resource->load($revision, $revisionId);
            if (!$revision->getId()) {
                throw NoSuchEntityException::singleField('buildifyRevisionId', $revisionId);
            }
            $this->registry[$revisionId] = $revision;
        }
        return $this->registry[$revisionId];
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aheadworks\Buildify\Model\ResourceModel\Revision\Collection $collection */
        $collection = $this->revisionCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var RevisionSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $objects = [];
        /** @var Revision $item */
        foreach ($collection->getItems() as $item) {
            $objects[] = $this->getDataObject($item);
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RevisionInterface $revision)
    {
        try {
            $this->resource->delete($revision);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        if (isset($this->registry[$revision->getId()])) {
            unset($this->registry[$revision->getId()]);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($id)
    {
        return $this->delete($this->get($id));
    }

    /**
     * Retrieves data object using model
     *
     * @param Revision $model
     * @return RevisionInterface
     */
    private function getDataObject($model)
    {
        /** @var RevisionInterface $object */
        $object = $this->revisionInterfaceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $object,
            $model->getData(),
            RevisionInterface::class
        );
        return $object;
    }
}
