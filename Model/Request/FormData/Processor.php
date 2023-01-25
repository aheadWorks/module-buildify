<?php
namespace Aheadworks\Buildify\Model\Request\FormData;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Api\Data\EntityFieldInterfaceFactory;

/**
 * Class Processor
 * @package Aheadworks\Buildify\Model\Request\FormData
 */
class Processor
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
     * Prepare form data
     *
     * @param $request
     * @param string $filedKey
     * @return EntityFieldInterface
     */
    public function getEntityField($request, $filedKey)
    {
        $extensionAttributes = $request->getPostValue('extension_attributes', []);

        $awEntityFields = $extensionAttributes['aw_entity_fields'] ?? [];

        $buildifyFormData = $awEntityFields[$filedKey] ?? [];

        $editorConfig = $buildifyFormData[EntityFieldInterface::EDITOR_CONFIG] ?? [];
        $buildifyId = $buildifyFormData[EntityFieldInterface::ID] ?? null;

        /** @var EntityFieldInterface $entityField */
        $entityField = $this->entityFieldFactory->create();
        $entityField
            ->setId($buildifyId)
            ->setEditorConfig($editorConfig);

        return $entityField;
    }
}
