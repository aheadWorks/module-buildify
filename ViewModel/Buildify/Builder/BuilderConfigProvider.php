<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder;

use Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig\ConverterFactory;

/**
 * Class BuilderConfigProvider
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder
 */
class BuilderConfigProvider
{
    /**
     * @var ConverterFactory
     */
    private $converterFactory;

    /**
     * @var array
     */
    private $config;

    /**
     * @param ConverterFactory $converterFactory
     * @param array $config
     */
    public function __construct(
        ConverterFactory $converterFactory,
        array $config
    ) {
        $this->converterFactory = $converterFactory;
        $this->config = $config;
    }

    /**
     * @param mixed $entity
     * @param string $htmlId
     * @return \Aheadworks\Buildify\Api\Data\BuilderConfigInterface|bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBuilderConfig($entity, $htmlId)
    {
        if(!isset($this->config[$htmlId])) {
            return false;
        }

        $converterType = $this->config[$htmlId];
        $converter = $this->converterFactory->create($converterType);

        return $converter->convert($entity);
    }
}