<?php
namespace Aheadworks\Buildify\Model\Proxy\File\Requirejs;

/**
 * Interface PathInterface
 * @package Aheadworks\Buildify\Model\Proxy\File\Requirejs
 */
interface PathInterface
{
   public const KEY = 'key';
   public const URL = 'url';
   public const EXTENSION = 'extension';

    /**
     * Retrieve path key for require js
     *
     * @return string
     */
    public function getKey();

    /**
     * Retrieve path url for require js
     *
     * @return string
     */
    public function getUrl();

    /**
     * Retrieve extension for require js
     *
     * @return string
     */
    public function getExtension();
}
