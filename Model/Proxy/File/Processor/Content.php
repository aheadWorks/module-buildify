<?php
namespace Aheadworks\Buildify\Model\Proxy\File\Processor;

use Aheadworks\Buildify\Model\Proxy\File\UrlManagement;

/**
 * Class Content
 * @package Aheadworks\Buildify\Model\Proxy\File\Processor
 */
class Content
{
    /**
     * @var UrlManagement
     */
    private $proxyUrlManagement;

    /**
     * @param UrlManagement $proxyUrlManagement
     */
    public function __construct(UrlManagement $proxyUrlManagement)
    {
        $this->proxyUrlManagement = $proxyUrlManagement;
    }

    /**
     * Process content
     *
     * @param string $fileContent
     * @param string $fileUrl
     * @return string
     */
    public function process($fileContent, $fileUrl)
    {
        $pattern = '/url\s*\(\s*+(?|"([^"]*+)"|\'([^\']*+)\'|(\S*+))\s*+\)/';

        $fileContent = preg_replace_callback(
            $pattern,
            function ($match) use ($fileUrl) {
                if (empty($match[1]) || strpos($match[1], './') === false) {
                    return $match[0];
                }

                $matchData = explode('/', $match[1]);
                $fileUrlData = explode('/', $fileUrl);
                //pop filename
                array_pop($fileUrlData);

                foreach ($matchData as $item) {
                    if ($item == '..') {
                        array_pop($fileUrlData);
                    }

                    if ($item == '.' || $item == '..') {
                        array_shift($matchData);
                    }
                }

                $pathUrl = implode('/', $fileUrlData) . '/';
                $filePath = implode('/', $matchData);

                return "url('" . $this->proxyUrlManagement->getProxyUrl($filePath, $pathUrl, true) . "')";
            },
            $fileContent);

        return $fileContent;
    }
}
