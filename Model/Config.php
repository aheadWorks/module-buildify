<?php
declare(strict_types=1);

namespace Aheadworks\Buildify\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Config\Model\Config\Backend\Encrypted;

/**
 * @package Aheadworks\Buildify\Model
 */
class Config
{
    /**#@+
     * Constants for config path
     */
    public const XML_PATH_ENABLED = 'aw_bfy/aw_bfy_setting/enabled';
    public const XML_PATH_PUBLIC_API_KEY = 'aw_bfy/aw_bfy_setting/public_api_key';
    public const XML_PATH_API_BASE_URL = 'aw_bfy/aw_bfy_setting/api_base_url';
    public const XML_PATH_PRIVATE_API_KEY = 'aw_bfy/aw_bfy_setting/private_api_key';
    public const XML_PATH_PREFIX_FOR_ENABLED_ENTITY_TYPE = 'aw_bfy/aw_bfy_setting/is_enabled_entity_';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Encrypted
     */
    private $encryptor;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Encrypted $encryptor
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Encrypted $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    /**
     * Is module enabled
     *
     * @param int|null $storeId
     * @return string
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPublicApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PUBLIC_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve api base url
     *
     * @return string
     */
    public function getApiBaseUrl()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_API_BASE_URL
        );
    }

    /**
     * Retrieve public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getPrivateApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PRIVATE_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Retrieve decrypted  public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDecryptedPublicApiKey($storeId = null)
    {
        $value = $this->getPublicApiKey($storeId);

        return $this->encryptor->processValue($value);
    }

    /**
     * Retrieve decrypted public api key
     *
     * @param int|null $storeId
     * @return string
     */
    public function getDecryptedPrivateApiKey($storeId = null)
    {
        $value = $this->getPrivateApiKey($storeId);

        return $this->encryptor->processValue($value);
    }

    /**
     * check whether public and private keys are set
     *
     * @return bool
     */
    public function isPossibleToProcess()
    {
        return $this->isEnabled() && !empty($this->getPublicApiKey()) && !empty($this->getPrivateApiKey());
    }

    /**
     * Check is enabled buildify for entity type
     *
     * @param string $type
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabledForEntityType(string $type, int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PREFIX_FOR_ENABLED_ENTITY_TYPE . $type,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
