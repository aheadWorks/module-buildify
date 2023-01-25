<?php
namespace Aheadworks\Buildify\Observer\PrepareFormData\Entity;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Aheadworks\Buildify\Model\Request\FormData\Processor as FormDataProcessor;
use Magento\Catalog\Api\Data\CategoryExtensionFactory;

/**
 * Class Category
 * @package Aheadworks\Buildify\Observer\PrepareFormData\Entity
 */
class Category implements ObserverInterface
{
    /**
     * @var FormDataProcessor
     */
    private $formDataProcessor;

    /**
     * @var CategoryExtensionFactory
     */
    private $extensionFactory;

    /**
     * @param FormDataProcessor $formDataProcessor
     * @param CategoryExtensionFactory $extensionFactory
     */
    public function __construct(
        FormDataProcessor $formDataProcessor,
        CategoryExtensionFactory $extensionFactory
    ) {
        $this->formDataProcessor = $formDataProcessor;
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * Prepare form data
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $category = $observer->getEvent()->getCategory();
        $request = $observer->getEvent()->getRequest();

        $entityField = $this->formDataProcessor->getEntityField(
            $request,
            EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE
        );

        $extensionAttributes = $category->getExtensionAttributes();
        if (!is_object($extensionAttributes)) {
            $extensionAttributes = $this->extensionFactory->create();
        }
        $awEntityFields = $extensionAttributes->getAwEntityFields() ?: [];
        $awEntityFields[EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE] = $entityField;
        $extensionAttributes->setAwEntityFields($awEntityFields);
        $category->setExtensionAttributes($extensionAttributes);
    }
}
