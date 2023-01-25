<?php
namespace Aheadworks\Buildify\ViewModel\Buildify\Page;

use Aheadworks\Buildify\Model\Proxy\File\UrlManagement;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Aheadworks\Buildify\Model\Proxy\File\Requirejs\PathMapPool;

/**
 * Class Preview
 * @package Aheadworks\Buildify\ViewModel\Buildify\Page
 */
class Preview implements ArgumentInterface
{
    /**
     * @var UrlManagement
     */
    private $proxyUrlManagement;

    /**
     * @var PathMapPool
     */
    private $pathMapPool;

    /**
     * @param UrlManagement $proxyUrlManagement
     * @param PathMapPool $pathMapPool
     */
    public function __construct(
        UrlManagement $proxyUrlManagement,
        PathMapPool $pathMapPool
    ) {
        $this->proxyUrlManagement = $proxyUrlManagement;
        $this->pathMapPool = $pathMapPool;
    }

    /**
     * Retrieve path files to remap
     *
     * @return \Aheadworks\Buildify\Model\Proxy\File\Requirejs\PathInterface[]
     */
    public function getPathFilesToRemap()
    {
        return $this->pathMapPool->getPathFiles();
    }

    /**
     * Retrieve proxy url
     *
     * @param string $fileUrl
     * @param string $baseThemeUrl
     * @return string
     */
    public function getProxyUrl($fileUrl, $baseThemeUrl)
    {
        return $this->proxyUrlManagement->getProxyUrl($fileUrl, $baseThemeUrl);
    }
}
