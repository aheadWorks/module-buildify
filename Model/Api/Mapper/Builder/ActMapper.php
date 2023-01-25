<?php
namespace Aheadworks\Buildify\Model\Api\Mapper\Builder;

use Aheadworks\Buildify\Model\Api\Mapper\MapperInterface;

/**
 * Class ActMapper
 * @package Aheadworks\Buildify\Model\Api\Mapper\Builder
 */
class ActMapper implements MapperInterface
{
    /**
     * {@inheritDoc}
     */
    public function toApi($data)
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function fromApi($data)
    {
        if (isset($data['data'])) {
            foreach ($data['data'] as $key => $value) {
                $data[$key] = $value;
            }
            unset($data['data']);
        }

        return $data;
    }
}
