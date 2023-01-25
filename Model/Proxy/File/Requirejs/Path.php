<?php
namespace Aheadworks\Buildify\Model\Proxy\File\Requirejs;

use Magento\Framework\DataObject;

/**
 * Class Path
 * @package Aheadworks\Buildify\Model\Proxy\File\Requirejs
 */
class Path extends DataObject implements PathInterface
{
    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->getData(self::KEY);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension()
    {
        return $this->getData(self::EXTENSION);
    }
}
