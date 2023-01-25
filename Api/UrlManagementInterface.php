<?php
namespace Aheadworks\Buildify\Api;

/**
 * Interface UrlManagementInterface
 * @package Aheadworks\Buildify\Api
 */
interface UrlManagementInterface
{
    /**
     * Build url by requested path and parameters
     *
     * @param string $routePath
     * @param array $routeParams
     * @param bool $addAdditional
     * @return string
     */
    public function getUrl($routePath, $routeParams = [], $addAdditional = true);

    /**
     * Retrieve frontend url
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    public function getFrontendUrl($path, $params = []);

    /**
     * Retrieve path from url
     *
     * @param string $url
     * @return string
     */
    public function getPath($url);
}
