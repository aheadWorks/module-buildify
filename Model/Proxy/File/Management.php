<?php
namespace Aheadworks\Buildify\Model\Proxy\File;

use Aheadworks\Buildify\Model\Proxy\File\Processor\Content;
use Magento\Framework\App\CacheInterface as CacheManager;
use Aheadworks\Buildify\Model\Proxy\Filesystem\Driver\Https;
use Aheadworks\Buildify\Model\Proxy\File\Context\Provider;

/**
 * Class Management
 * @package Aheadworks\Buildify\Model\Proxy\File
 */
class Management
{
    /**#@+
     * Constants for cache identifiers
     */
    const CONTENT_CACHE_IDENTIFIER_PREFIX = 'aw_dby_proxy_content_';
    const CONTENT_TYPE_CACHE_IDENTIFIER_PREFIX = 'aw_dby_proxy_content_type_';
    /**#@-/

    /**
     * @var UrlManagement
     */
    private $proxyUrlManagement;

    /**
     * @var SupportedType
     */
    private $supportedType;

    /**
     * @var Https
     */
    private $httpsDriver;

    /**
     * @var Content
     */
    private $contentProcessor;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Provider
     */
    private $contextProvider;

    /**
     * @param UrlManagement $proxyUrlManagement
     * @param SupportedType $supportedType
     * @param Content $contentProcessor
     * @param Https $httpsDriver
     * @param CacheManager $cacheManager
     * @param Provider $contextProvider
     */
    public function __construct(
        UrlManagement $proxyUrlManagement,
        SupportedType $supportedType,
        Content $contentProcessor,
        Https $httpsDriver,
        CacheManager $cacheManager,
        Provider $contextProvider
    ) {
        $this->proxyUrlManagement = $proxyUrlManagement;
        $this->supportedType = $supportedType;
        $this->contentProcessor = $contentProcessor;
        $this->httpsDriver = $httpsDriver;
        $this->cacheManager = $cacheManager;
        $this->contextProvider = $contextProvider;
    }

    /**
     * Retrieve content
     *
     * @param string $fileUrl
     * @param string $baseThemeUrl
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getContent($fileUrl)
    {
        $contentIdentifier = self::CONTENT_CACHE_IDENTIFIER_PREFIX . $fileUrl;

        if ($fileContent = $this->cacheManager->load($contentIdentifier)) {
            return $fileContent;
        }

        $fileContent = $this->httpsDriver->fileGetContents($fileUrl, false, $this->contextProvider->getContext());
        $type = $this->getContentType($fileUrl);

        if (in_array($type, $this->supportedType->getProcessorContentTypes())) {
            $fileContent = $this->contentProcessor->process($fileContent, $fileUrl);
        }
        $this->cacheManager->save($fileContent, $contentIdentifier);

        return $fileContent;
    }

    /**
     * Retrieve content type
     *
     * @param string $fileUrl
     * @return string
     */
    public function getContentType($fileUrl)
    {
        $contentTypeIdentifier = self::CONTENT_TYPE_CACHE_IDENTIFIER_PREFIX . $fileUrl;

        if ($type = $this->cacheManager->load($contentTypeIdentifier)) {
            return $type;
        }

        $type = $this->httpsDriver->statWithContext($fileUrl, $this->contextProvider->getContext())['type'];
        $this->cacheManager->save($type, $contentTypeIdentifier);

        return $type;
    }
}
