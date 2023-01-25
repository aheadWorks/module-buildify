<?php
namespace Aheadworks\Buildify\Model\Buildify;

use Aheadworks\Buildify\Api\TemplateRepositoryInterface;
use Aheadworks\Buildify\Api\Data\TemplateInterfaceFactory;
use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Aheadworks\Buildify\Api\Data\TemplateSearchResultsInterface;
use Aheadworks\Buildify\Api\Data\TemplateSearchResultsInterfaceFactory;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Template as TemplateResourceModel;
use Aheadworks\Buildify\Model\ResourceModel\Buildify\Template\CollectionFactory as TemplateCollectionFactory;
use Aheadworks\Buildify\Model\Buildify\Template;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class TemplateRepository
 * @package Aheadworks\Buildify\Model\Buildify
 */
class TemplateRepository implements TemplateRepositoryInterface
{
    /**
     * @var TemplateResourceModel
     */
    private $resource;

    /**
     * @var TemplateInterfaceFactory
     */
    private $templateInterfaceFactory;

    /**
     * @var TemplateCollectionFactory
     */
    private $templateCollectionFactory;

    /**
     * @var TemplateSearchResultsInterfaceFactory
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
     * @param TemplateResourceModel $resource
     * @param TemplateInterfaceFactory $templateInterfaceFactory
     * @param TemplateCollectionFactory $templateCollectionFactory
     * @param TemplateSearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        TemplateResourceModel $resource,
        TemplateInterfaceFactory $templateInterfaceFactory,
        TemplateCollectionFactory $templateCollectionFactory,
        TemplateSearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->templateInterfaceFactory = $templateInterfaceFactory;
        $this->templateCollectionFactory = $templateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function save(TemplateInterface $template)
    {
        try {
            $this->resource->save($template);
            $this->registry['id'][$template->getId()] = $template;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function get($templateId)
    {
        if (!isset($this->registry['id'][$templateId])) {
            /** @var TemplateInterface $template */
            $template = $this->templateInterfaceFactory->create();
            $this->resource->load($template, $templateId);
            if (!$template->getId()) {
                throw NoSuchEntityException::singleField('templateId', $templateId);
            }
            $this->registry['id'][$templateId] = $template;
            $this->registry['external_id'][$template->getExternalId()] = $template;
        }
        return $this->registry['id'][$templateId];
    }

    /**
     * {@inheritdoc}
     */
    public function getByExternalId($externalId)
    {
        if (!isset($this->registry['external_id'][$externalId])) {
            /** @var TemplateInterface $template */
            $template = $this->templateInterfaceFactory->create();
            $this->resource->load($template, $externalId, TemplateInterface::EXTERNAL_ID);
            if (!$template->getId()) {
                throw NoSuchEntityException::singleField('templateId', $externalId);
            }
            $this->registry['external_id'][$externalId] = $template;
            $this->registry['id'][$template->getId()] = $template;
        }
        return $this->registry['external_id'][$externalId];
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aheadworks\Buildify\Model\ResourceModel\Buildify\Template\Collection $collection */
        $collection = $this->templateCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var TemplateSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $objects = [];
        /** @var Template $item */
        foreach ($collection->getItems() as $item) {
            $objects[] = $this->getDataObject($item);
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TemplateInterface $template)
    {
        try {
            $this->resource->delete($template);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        if (isset($this->registry['id'][$template->getId()])) {
            unset($this->registry['id'][$template->getId()]);
        }
        if (isset($this->registry['external_id'][$template->getExternalId()])) {
            unset($this->registry['external_id'][$template->getExternalId()]);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByExternalId($externalId)
    {
        return $this->delete($this->getByExternalId($externalId));
    }

    /**
     * Retrieves data object using model
     *
     * @param Template $model
     * @return TemplateInterface
     */
    private function getDataObject($model)
    {
        /** @var TemplateInterface $object */
        $object = $this->templateInterfaceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $object,
            $model->getData(),
            TemplateInterface::class
        );
        return $object;
    }
}
