<?php
namespace Aheadworks\Buildify\Api;

/**
 * Interface EntityRepositoryInterface
 * @package Aheadworks\Buildify\Api
 */
interface EntityRepositoryInterface
{
    /**
     * Save entity field
     *
     * @param \Aheadworks\Buildify\Api\Data\EntityFieldInterface $entityField
     * @param int $entityId
     * @param string $entityType
     * @return \Aheadworks\Buildify\Api\Data\EntityFieldInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Aheadworks\Buildify\Api\Data\EntityFieldInterface $entityField, $entityId, $entityType);

    /**
     * Retrieve entity field by id and type
     *
     * @param int $entityId
     * @param string $entityType
     * @return \Aheadworks\Buildify\Api\Data\EntityFieldInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($entityId, $entityType);

    /**
     * Delete entity field
     *
     * @param int $entityId
     * @param string $entityType
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete($entityId, $entityType);

    /**
     * Delete entity field by id
     *
     * @param int $entityFieldId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    public function deleteById($entityFieldId);

    /**
     * Retrieve entity list matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Buildify\Api\Data\EntityFieldSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}