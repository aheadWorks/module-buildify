<?php
namespace Aheadworks\Buildify\Model\Proxy\File\Context\Options;

/**
 * Interface OptionProviderInterface
 * @package Aheadworks\Buildify\Model\Proxy\File\Context\Options
 */
interface OptionProviderInterface
{
    /**
     * Retrieve context options
     *
     * @return array|boolean
     */
    public function getOptions();
}