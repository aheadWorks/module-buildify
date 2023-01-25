<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Builder;

/**
 * Interface EntityLocatorInterface
 * @package Aheadworks\Buildify\ViewModel\Buildify\Builder
 */
interface EntityLocatorInterface
{
    /**
     * @return \Aheadworks\Buildify\Api\Data\BuilderConfigInterface|bool
     */
    public function getEntity();
}
