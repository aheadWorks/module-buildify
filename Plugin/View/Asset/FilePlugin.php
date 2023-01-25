<?php
namespace Aheadworks\Buildify\Plugin\View\Asset;

use Aheadworks\Buildify\Model\Proxy\File\SupportedType;
use Aheadworks\Buildify\Model\Proxy\File\UrlManagement;
use Magento\Framework\View\Asset\File;
use Magento\Framework\App\RequestInterface;

/**
 * Class FilePlugin
 * @package Aheadworks\Buildify\Plugin\View\Asset
 */
class FilePlugin
{
    /**
     * @var UrlManagement
     */
    private $proxyUrlManagement;

    /**
     * @var SupportedType
     */
    private $supportedType;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param UrlManagement $proxyUrlManagement
     * @param SupportedType $supportedType
     * @param RequestInterface $request
     */
    public function __construct(
        UrlManagement $proxyUrlManagement,
        SupportedType $supportedType,
        RequestInterface $request
    ) {
        $this->proxyUrlManagement = $proxyUrlManagement;
        $this->supportedType = $supportedType;
        $this->request = $request;
    }

    /**
     * @param File $subject
     * @param string $result
     * @return string
     */
    public function afterGetUrl($subject, $result)
    {
        if ($this->request->getModuleName() == 'aw_buildify'
            && $this->request->getControllerName() == 'page'
            && $this->request->getActionName() == 'preview'
            && in_array($subject->getContentType(), $this->supportedType->getProxyFileTypes())
        ) {
            $baseThemeUrl = $subject->getContext()->getBaseUrl() . $subject->getContext()->getPath() . '/';
            $result = $this->proxyUrlManagement->getProxyUrl($result, $baseThemeUrl);
        }
        return $result;
    }
}
