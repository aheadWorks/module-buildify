<?php
namespace Aheadworks\Buildify\Setup\Patch\Data;

use Aheadworks\Buildify\Model\ResourceModel\EntityField as BuildifyResourceEntity;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class DeleteDuplicateEntityField implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var BuildifyResourceEntity
     */
    private $buildifyResourceEntity;

    /**
     * Updater constructor.
     * @param BuildifyResourceEntity $buildifyResourceEntity
     */
    public function __construct(
        BuildifyResourceEntity $buildifyResourceEntity
    ) {
        $this->buildifyResourceEntity = $buildifyResourceEntity;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->buildifyResourceEntity->deleteDuplicate();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '1.0.2';
    }
}