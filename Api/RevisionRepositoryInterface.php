<?php
namespace Aheadworks\Buildify\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface RevisionRepositoryInterface
 * @package Aheadworks\Buildify\Api
 */
interface RevisionRepositoryInterface
{
    /**
     * Save revision
     *
     * @param \Aheadworks\Buildify\Api\Data\RevisionInterface $revision
     * @return \Aheadworks\Buildify\Api\Data\RevisionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Buildify\Api\Data\RevisionInterface $revision);

    /**
     * Retrieve revision by id with storefront labels for specified store view
     *
     * @param int $revisionId
     * @return \Aheadworks\Buildify\Api\Data\RevisionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($revisionId);

    /**
     * Retrieve spaces matching the specified criteria with storefront labels for specified store view
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Buildify\Api\Data\RevisionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete revision
     *
     * @param \Aheadworks\Buildify\Api\Data\RevisionInterface $revision
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Buildify\Api\Data\RevisionInterface $revision);

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
