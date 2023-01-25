<?php
namespace Aheadworks\Buildify\Model\Api\Mapper;

use Aheadworks\Buildify\Api\UrlManagementInterface;

/**
 * Class MapperManager
 * @package Aheadworks\Buildify\Model\Api\Mapper
 */
class MapperManager
{
    /**
     * @var UrlManagementInterface
     */
    private $urlManagement;

    /**
     * @var array
     */
    private $mappers;

    /**
     * @param UrlManagementInterface $urlManagement
     * @param array $mappers
     */
    public function __construct(
        UrlManagementInterface $urlManagement,
        array $mappers
    ) {
        $this->urlManagement = $urlManagement;
        $this->mappers = $mappers;
    }

    /**
     * Map response data from api
     *
     * @param string $url
     * @param array $data
     * @return array
     */
    public function fromApi($url, $data)
    {
        $method = $this->urlManagement->getPath($url);
        if (isset($this->mappers[$method])) {
            $data = $this->mappers[$method]->fromApi($data);
        }

        return $data;
    }
}
