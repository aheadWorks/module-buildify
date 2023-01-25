<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Revision;

use Aheadworks\Buildify\Api\Data\RevisionInterface;
use Aheadworks\Buildify\Api\RevisionRepositoryInterface;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\Revision\DataProvider\Converter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

/**
 * Class DataProvider
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Revision
 */
class DataProvider
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var RevisionRepositoryInterface
     */
    private $revisionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param Converter $converter
     * @param RevisionRepositoryInterface $revisionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        Converter $converter,
        RevisionRepositoryInterface $revisionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->converter = $converter;
        $this->revisionRepository = $revisionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * Retrieve data
     *
     * @param int $bfyEntityId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getData($bfyEntityId)
    {
        $this->sortOrderBuilder->setField(RevisionInterface::CREATED_AT)->setDescendingDirection();
        $this->searchCriteriaBuilder
            ->addFilter(RevisionInterface::BFY_ENTITY_ID, $bfyEntityId)
            ->addSortOrder($this->sortOrderBuilder->create());
        $revisionItems = $this->revisionRepository->getList($this->searchCriteriaBuilder->create())->getItems();

        return $this->prepareData($revisionItems);
    }

    /**
     * @param RevisionInterface|RevisionInterface[] $revisionItems
     * @return array
     */
    private function prepareData($revisionItems)
    {
        $result = [];
        $revisionItems = is_array($revisionItems) ? $revisionItems : [$revisionItems];
        foreach ($revisionItems as $revisionItem) {
            $result[] = $this->converter->convert($revisionItem);
        }
        return $result;
    }
}
