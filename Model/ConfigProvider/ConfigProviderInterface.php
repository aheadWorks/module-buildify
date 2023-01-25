<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

/**
 * Interface ConfigProviderInterface
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
interface ConfigProviderInterface
{
    /**
     * Retrieve config
     *
     * @return array
     */
    public function getConfig();
}
