<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

use Aheadworks\Buildify\Model\Entity\Config;

/**
 * Class FormConfigProvider
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
class FormConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        $output['enabledComponentTypes'] = $this->config->getEnabledComponentType();

        return $output;
    }
}
