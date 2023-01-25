<?php
namespace Aheadworks\Buildify\Model\Proxy\File\Requirejs;

/**
 * Class PathMapPool
 * @package Aheadworks\Buildify\Model\Proxy\File\Requirejs
 */
class PathMapPool
{
    /**
     * @var PathInterfaceFactory
     */
    private $pathFactory;

    /**
     * @var array
     */
    private $pathFiles = [];

    /**
     * @var PathInterface[]
     */
    private $instances = [];

    /**
     * @param PathInterfaceFactory $pathFactory
     * @param array $pathFiles
     */
    public function __construct(
        PathInterfaceFactory $pathFactory,
        array $pathFiles
    ) {
        $this->pathFiles = $pathFiles;
        $this->pathFactory = $pathFactory;
    }

    /**
     * Retrieves path files
     *
     * @return PathInterface[]
     */
    public function getPathFiles()
    {
        if (!$this->instances) {
            foreach ($this->pathFiles as $pathFile) {
                $this->instances[] = $this->pathFactory->create(['data' => $pathFile]);
            }
        }
        return $this->instances;
    }
}
