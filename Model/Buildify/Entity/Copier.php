<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Api\Data\EntityFieldInterfaceFactory;

/**
 * Class Copier
 * @package Aheadworks\Buildify\Model\Buildify\Entity
 */
class Copier
{
    /**
     * @var EntityFieldInterfaceFactory
     */
    private $entityFieldFactory;

    /**
     * @param EntityFieldInterfaceFactory $entityFieldFactory
     */
    public function __construct(
        EntityFieldInterfaceFactory $entityFieldFactory
    ) {
        $this->entityFieldFactory = $entityFieldFactory;
    }

    /**
     * Copy and retrieve duplicated entity field object
     *
     * @param EntityFieldInterface $entityField
     * @return EntityFieldInterface
     */
    public function copy(EntityFieldInterface $entityField)
    {
        /** @var EntityFieldInterface $entityField */
        $duplicate = $this->entityFieldFactory->create();

        $duplicate->setEntityType($entityField->getEntityType());
        $duplicate->setCssStyle($entityField->getCssStyle());
        $duplicate->setEditorConfig($entityField->getEditorConfig());

        return $duplicate;
    }
}