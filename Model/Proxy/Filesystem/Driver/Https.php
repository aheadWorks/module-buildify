<?php
namespace Aheadworks\Buildify\Model\Proxy\Filesystem\Driver;

use Magento\Framework\Filesystem\Driver\Https as DriverHttps;

/**
 * Class Https
 * @package Aheadworks\Buildify\Model\Proxy\Filesystem\Driver
 */
class Https extends DriverHttps
{
    /**
     * @var string
     */
    protected $scheme = '';

    /**
     * Gathers the statistics of the given path
     *
     * @param string $path
     * @param resource $context
     * @return array
     */
    public function statWithContext($path, $context)
    {
        $headers = array_change_key_case(
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
            get_headers($this->getScheme() . $path, 1, $context),
            CASE_LOWER
        );

        $result = [
            'dev' => 0,
            'ino' => 0,
            'mode' => 0,
            'nlink' => 0,
            'uid' => 0,
            'gid' => 0,
            'rdev' => 0,
            'atime' => 0,
            'ctime' => 0,
            'blksize' => 0,
            'blocks' => 0,
            'size' => isset($headers['content-length']) ? $headers['content-length'] : 0,
            'type' => isset($headers['content-type']) ? $headers['content-type'] : '',
            'mtime' => isset($headers['last-modified']) ? $headers['last-modified'] : 0,
            'disposition' => isset($headers['content-disposition']) ? $headers['content-disposition'] : null,
        ];

        return $result;
    }
}