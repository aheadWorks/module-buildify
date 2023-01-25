<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig;

/**
 * Class ConverterFactory
 * @package Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig
 */
class ConverterFactory
{
    /**
     * @var array
     */
    private $types;

    /**
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @param string $type
     * @return Converter
     */
    public function create($type)
    {
        return $this->types[$type];
    }
}
