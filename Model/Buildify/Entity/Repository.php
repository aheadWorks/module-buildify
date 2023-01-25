<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity;

use Aheadworks\Buildify\Api\EntityRepositoryInterface;
use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Api\Data\EntityFieldInterfaceFactory;
use Aheadworks\Buildify\Model\EntityField;
use Aheadworks\Buildify\Model\ResourceModel\EntityField as BuildifyResourceEntity;
use Aheadworks\Buildify\Api\Data\EntityFieldSearchResultInterface;
use Aheadworks\Buildify\Api\Data\EntityFieldSearchResultInterfaceFactory;
use Aheadworks\Buildify\Model\ResourceModel\EntityField\CollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class Repository
 * @package Aheadworks\Buildify\Model\Buildify\Entity
 */
class Repository implements EntityRepositoryInterface
{
    /**
     * @var BuildifyResourceEntity
     */
    private $resource;

    /**
     * @var EntityFieldInterfaceFactory
     */
    private $entityFieldFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var EntityFieldSearchResultInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var array
     */
    private $registry = [];

    /**
     * @param BuildifyResourceEntity $resource
     * @param EntityFieldInterfaceFactory $entityFieldFactory
     * @param CollectionFactory $collectionFactory
     * @param EntityFieldSearchResultInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        BuildifyResourceEntity $resource,
        EntityFieldInterfaceFactory $entityFieldFactory,
        CollectionFactory $collectionFactory,
        EntityFieldSearchResultInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->resource = $resource;
        $this->entityFieldFactory = $entityFieldFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function save(EntityFieldInterface $entityField, $entityId, $entityType)
    {
        try {
            $entityField
                ->setEntityId($entityId)
                ->setEntityType($entityType);
            $entityField->setId($entityField->getId() ? : null);
            $this->resource->save($entityField);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $entityField;
    }

    /**
     * {@inheritdoc}
     */
    public function get($entityId, $entityType)
    {
        $registryKey = implode('-', [$entityId, $entityType]);
        if (!isset($this->registry[$registryKey])) {
            $id = $this->resource->getIdByEntityData($entityId, $entityType);
            if (!$id) {
                throw NoSuchEntityException::singleField('id', $entityId);
            }

            /** @var EntityFieldInterface $entityField */
            $entityField = $this->entityFieldFactory->create();
            $this->resource->load($entityField, $id);
            $this->registry[$registryKey] = $entityField;
        }

        return $this->registry[$registryKey];
    }

    /**
     * {@inheritdoc}
     */
    public function delete($entityId, $entityType)
    {
        try {
            $id = $this->resource->getIdByEntityData($entityId, $entityType);
            if (!$id) {
                return false;
            }

            return $this->deleteById($id);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($entityFieldId)
    {
        $entityField = $this->entityFieldFactory->create();
        $this->resource->load($entityField, $entityFieldId);
        if (!$entityField->getId()) {
            throw NoSuchEntityException::singleField('Id', $entityFieldId);
        }
        $this->resource->delete($entityField);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aheadworks\Buildify\Model\ResourceModel\EntityField\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var EntityFieldSearchResultInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $objects = [];
        /** @var EntityField $item */
        foreach ($collection->getItems() as $item) {
            $objects[] = $this->getDataObject($item);
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * Retrieves data object using model
     *
     * @param EntityField $model
     * @return EntityFieldInterface
     */
    private function getDataObject(EntityField $model)
    {
        /** @var EntityFieldInterface $object */
        $object = $this->entityFieldFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $object,
            $this->dataObjectProcessor->buildOutputDataArray($model, EntityFieldInterface::class),
            EntityFieldInterface::class
        );
        return $object;
    }
}
