<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

/**
 * Class DemotourConfigProvider
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
class DemotourConfigProvider implements ConfigProviderInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(
        array $config = []
    ) {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        $output['demotour'] = $this->config;

        return $output;
    }
}