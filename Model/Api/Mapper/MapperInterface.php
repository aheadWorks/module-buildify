<?php
namespace Aheadworks\Buildify\Model\Api\Mapper;

/**
 * Class MapperInterface
 * @package Aheadworks\Buildify\Model\Api\Mapper
 */
interface MapperInterface
{
    /**
     * Map request data to api
     *
     * @param array $data
     * @return array
     */
    public function toApi($data);

    /**
     * Map response data from api
     *
     * @param array $data
     * @return array
     */
    public function fromApi($data);
}
