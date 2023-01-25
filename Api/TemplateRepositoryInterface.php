<?php
namespace Aheadworks\Buildify\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface TemplateRepositoryInterface
 * @package Aheadworks\Buildify\Api
 */
interface TemplateRepositoryInterface
{
    /**
     * Save template
     *
     * @param \Aheadworks\Buildify\Api\Data\TemplateInterface $template
     * @return \Aheadworks\Buildify\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Buildify\Api\Data\TemplateInterface $template);

    /**
     * Retrieve template by id with storefront labels for specified store view
     *
     * @param int $templateId
     * @return \Aheadworks\Buildify\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($templateId);

    /**
     * Retrieve template by id with storefront labels for specified store view
     *
     * @param int $externalId
     * @return \Aheadworks\Buildify\Api\Data\TemplateInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByExternalId($externalId);

    /**
     * Retrieve spaces matching the specified criteria with storefront labels for specified store view
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Buildify\Api\Data\TemplateSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete template
     *
     * @param \Aheadworks\Buildify\Api\Data\TemplateInterface $template
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Buildify\Api\Data\TemplateInterface $template);

    /**
     * Delete by externalId
     *
     * @param int $externalId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteByExternalId($externalId);
}
