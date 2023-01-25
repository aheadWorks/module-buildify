<?php
namespace Aheadworks\Buildify\Model\Service;

use Aheadworks\Buildify\Api\UrlManagementInterface;
use Aheadworks\Buildify\Model\VersionChecker;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Url;
use Aheadworks\Buildify\Model\Config;

/**
 * Class UrlService
 * @package Aheadworks\Buildify\Model\Service
 */
class UrlService implements UrlManagementInterface
{
    /**
     * platform name
     */
    public const PLATFORM = 'magento';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Url
     */
    private $frontendUrl;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var VersionChecker
     */
    private $versionChecker;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Url $frontendUrl
     * @param ScopeConfigInterface $scopeConfig
     * @param VersionChecker $versionChecker
     * @param Config $config
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Url $frontendUrl,
        ScopeConfigInterface $scopeConfig,
        VersionChecker $versionChecker,
        Config $config
    ) {
        $this->storeManager = $storeManager;
        $this->frontendUrl = $frontendUrl;
        $this->scopeConfig = $scopeConfig;
        $this->versionChecker = $versionChecker;
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($routePath, $routeParams = [], $addAdditional = true)
    {
        if ($addAdditional) {
            $routeParams['platform'] = self::PLATFORM;
            // @todo refactoring
            $routeParams['shop'] = $this->getStoreUrl();
            $routeParams['version'] = $this->versionChecker->getModuleVersion();
            $routeParams = '?' . http_build_query($routeParams);
            $routePath = '/' . $routePath;
        } else {
            $routePath = $routeParams = '';
        }

        $apiBaseUrl = $this->prepareApiBaseUrl($this->config->getApiBaseUrl());

        return $apiBaseUrl . $routePath . $routeParams;
    }

    /**
     * {@inheritDoc}
     */
    public function getFrontendUrl($path, $params = [])
    {
        return $this->frontendUrl->getUrl($path, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($url)
    {
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        return parse_url($url, PHP_URL_PATH);
    }

    /**
     * Retrieve store url
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStoreUrl()
    {
        $store = $this->storeManager->getStore();
        $isSecure = $this->scopeConfig->isSetFlag(
            'web/secure/use_in_frontend',
            ScopeInterface::SCOPE_STORE,
            $store
        );

        return rtrim($store->getBaseUrl('link', $isSecure), '/');
    }

    /**
     * Retrieve prepared api base url
     *
     * @param $apiBaseUrl
     * @return string
     */
    private function prepareApiBaseUrl($apiBaseUrl)
    {
        return rtrim($apiBaseUrl,'/');
    }
}
