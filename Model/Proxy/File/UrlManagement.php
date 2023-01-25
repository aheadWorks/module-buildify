<?php
namespace Aheadworks\Buildify\Model\Proxy\File;

use Aheadworks\Buildify\Model\Url\ParamEncryptor;
use Magento\Framework\UrlInterface;

/***
 * Class UrlManagement
 * @package Aheadworks\Buildify\Model\Proxy\File
 */
class UrlManagement
{
    const FILE_URL = 'fileUrl';
    const BASE_THEME_URL = 'baseThemeUrl';

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var ParamEncryptor
     */
    private $paramEncryptor;

    /**
     * @param UrlInterface $url
     * @param ParamEncryptor $paramEncryptor
     */
    public function __construct(
        UrlInterface $url,
        ParamEncryptor $paramEncryptor
    ) {
        $this->url = $url;
        $this->paramEncryptor = $paramEncryptor;
    }

    /**
     * Retrieve proxy url
     *
     * @param string $fileUrl
     * @param string $baseThemeUrl
     * @param bool $addBaseUrl
     * @return string
     */
    public function getProxyUrl($fileUrl, $baseThemeUrl, $addBaseUrl = false)
    {
        $params = [
            self::FILE_URL => $addBaseUrl ? $baseThemeUrl . $fileUrl : $fileUrl,
            self::BASE_THEME_URL => $baseThemeUrl
        ];
        $hash = $this->paramEncryptor->encrypt($params);

        return $this->url->getUrl('aw_buildify/page/preview_fileProxy', ['hash' => $hash]);
    }

    /**
     * Retrieve File Url
     *
     * @param string $hash
     * @return string
     */
    public function getFileUrl($hash)
    {
        return $this->paramEncryptor->decrypt(self::FILE_URL, $hash);
    }

    /**
     * Retrieve Base Theme Url
     *
     * @param string $hash
     * @return string
     */
    public function getBaseThemeUrl($hash)
    {
        return $this->paramEncryptor->decrypt(self::BASE_THEME_URL, $hash);
    }
}
