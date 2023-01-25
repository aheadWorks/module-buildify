<?php
namespace Aheadworks\Buildify\ViewModel\Buildify;

use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\Api\UrlManagementInterface;
use Aheadworks\Buildify\Model\Entity\Config;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\EntityLocator;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Aheadworks\Buildify\Model\ConfigProvider;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\BuilderConfigProvider;

/**
 * Class Builder
 * @package Aheadworks\Buildify\ViewModel\Buildify
 */
class Builder implements ArgumentInterface
{
    /**
     * @var EntityLocator
     */
    private $entityLocator;

    /**
     * @var RequestManagementInterface
     */
    private $requestManagement;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var BuilderConfigProvider
     */
    private $builderConfigProvider;

    /**
     * @var UrlManagementInterface
     */
    private $urlManagement;

    /**
     * @param EntityLocator $entityLocator
     * @param RequestManagementInterface $requestManagement
     * @param Json $serializer
     * @param ConfigProvider $configProvider
     * @param BuilderConfigProvider $builderConfigProvider
     * @param UrlManagementInterface $urlManagement
     */
    public function __construct(
        EntityLocator $entityLocator,
        RequestManagementInterface $requestManagement,
        Json $serializer,
        ConfigProvider $configProvider,
        BuilderConfigProvider $builderConfigProvider,
        UrlManagementInterface $urlManagement
    ) {
        $this->entityLocator = $entityLocator;
        $this->requestManagement = $requestManagement;
        $this->configProvider = $configProvider;
        $this->serializer = $serializer;
        $this->builderConfigProvider = $builderConfigProvider;
        $this->urlManagement = $urlManagement;
    }

    /**
     * Retrieve url for frame load
     *
     * @return string
     */
    public function getFrameUrl()
    {
        return $this->requestManagement->getFrameUrl();
    }

    /**
     * Retrieve url of client file
     *
     * @return string
     */
    public function getClientUrl()
    {
        $routePath = 'buildify/js/client.js';

        return $this->urlManagement->getUrl($routePath);
    }

    /**
     * Retrieve params for frame load
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFrameUrlParamsJson($htmlId)
    {
        try {
            $entity = $this->entityLocator->getEntity();
            $builderConfig = $this->builderConfigProvider->getBuilderConfig($entity, $htmlId);

            $response = $this->serializer->serialize($this->requestManagement->getFrameUrlParams($builderConfig));
        } catch (\Exception $e) {
            $response = null;
        }

        return $response;
    }

    /**
     * Retrieve serialized config
     *
     * @return bool|string
     */
    public function getSerializedConfig()
    {
        return $this->serializer->serialize($this->getConfigProvider());
    }

    /**
     * Check whether is modal window allowed
     *
     * @return bool
     */
    public function isModalEnabled()
    {
        $config = $this->getConfigProvider();

        return $config[Config::IS_MODAL];
    }

    public function getConfigProvider()
    {
      return $this->configProvider->getConfig();
    }
}
