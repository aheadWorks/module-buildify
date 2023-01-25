<?php
namespace Aheadworks\Buildify\Model\ConfigProvider;

use Aheadworks\Buildify\Model\Entity\Config;

/**
 * Class ModalConfigProvider
 * @package Aheadworks\Buildify\Model\ConfigProvider
 */
class ModalConfigProvider implements ConfigProviderInterface
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
        $output[Config::IS_MODAL] = $this->config->getIsModal();

        return $output;
    }
}
