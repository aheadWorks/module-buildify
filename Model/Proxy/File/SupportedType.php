<?php
namespace Aheadworks\Buildify\Model\Proxy\File;

/**
 * Class SupportedType
 * @package Aheadworks\Buildify\Model\Proxy\File
 */
class SupportedType
{
    /**
     * Retrieve file types to be downloaded through proxy
     *
     * @return array
     */
    public function getProxyFileTypes()
    {
        return ['css', 'woff', 'woff2'];
    }

    /**
     * Retrieve content types to be processed through content processor
     *
     * @return array
     */
    public function getProcessorContentTypes()
    {
        return ['text/css'];
    }
}
