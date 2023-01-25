<?php
namespace Aheadworks\Buildify\Api;

/**
 * Interface EntityFieldManagementInterface
 * @package Aheadworks\Buildify\Api
 */
interface EntityFieldManagementInterface
{
    /**
     * Remove domain from image path
     *
     * @return bool
     */
    public function removeDomainFromImgPath();

    /**
     * Re save entities
     *
     * @return bool
     */
    public function reSaveEntities();
}
