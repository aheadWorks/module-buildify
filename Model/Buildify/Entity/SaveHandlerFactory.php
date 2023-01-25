<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity;

/**
 * Class SaveHandlerFactory
 * @package Aheadworks\Buildify\Model\Buildify\Entity
 */
class SaveHandlerFactory
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
     * @return SaveHandler
     */
    public function create($type)
    {
        return $this->types[$type];
    }
}
