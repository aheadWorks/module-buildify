<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder\Template;

use Aheadworks\Buildify\Api\TemplateRepositoryInterface;
use Aheadworks\Buildify\Api\Data\TemplateInterface;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\Template\DataProvider\Converter;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class DataProvider
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder\Template
 */
class DataProvider
{
    /**
     * @var Converter
     */
    private $converter;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param Converter $converter
     * @param TemplateRepositoryInterface $templateRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        Converter $converter,
        TemplateRepositoryInterface $templateRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->converter = $converter;
        $this->templateRepository = $templateRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Retrieve data
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getData()
    {
        $templateItems = $this->templateRepository->getList($this->searchCriteriaBuilder->create())->getItems();

        return $this->prepareData($templateItems);
    }

    /**
     * @param TemplateInterface|TemplateInterface[] $templateItems
     * @return array
     */
    private function prepareData($templateItems)
    {
        $result = [];
        $templateItems = is_array($templateItems) ? $templateItems : [$templateItems];
        foreach ($templateItems as $templateItem) {
            $result[] = $this->converter->convert($templateItem);
        }
        return $result;
    }
}
