<?php
namespace Aheadworks\Buildify\Plugin\Model\ResourceModel;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\DeleteHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\SaveHandlerFactory;
use Magento\Catalog\Api\Data\CategoryInterface;

/**
 * Class CategoryPlugin
 * @package Aheadworks\Buildify\Plugin\Model\ResourceModel
 */
class CategoryPlugin
{
    /**
     * @var SaveHandlerFactory
     */
    private $saveHandlerFactory;

    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * @var DeleteHandler
     */
    private $deleteHandler;

    /**
     * @param SaveHandlerFactory $saveHandlerFactory
     * @param LoadHandler $loadHandler
     * @param DeleteHandler $deleteHandler
     */
    public function __construct(
        SaveHandlerFactory $saveHandlerFactory,
        LoadHandler $loadHandler,
        DeleteHandler $deleteHandler
    ) {
        $this->saveHandlerFactory = $saveHandlerFactory;
        $this->loadHandler = $loadHandler;
        $this->deleteHandler = $deleteHandler;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Page $subject
     * @param callable $proceed
     * @param CategoryInterface $category
     * @return \Magento\Cms\Model\ResourceModel\Page
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function aroundSave($subject, $proceed, $category)
    {
        $awEntityFields = $category->getExtensionAttributes()->getAwEntityFields();

        if (isset($awEntityFields[EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE])) {
            $saveHandler = $this->saveHandlerFactory->create(EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE);
            $result = $saveHandler->save($category, $proceed);
        } else {
            $result = $proceed($category);
        }

        return $result;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category $subject
     * @param \Magento\Catalog\Model\ResourceModel\Category $result
     * @param CategoryInterface $category
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterLoad($subject, $result, $category)
    {
        $awEntityField = $this->loadHandler->load(
            $category->getId(),
            EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE
        );
        $extensionAttributes = $category->getExtensionAttributes();
        $awEntityFields = $extensionAttributes->getAwEntityFields() ?: [];
        $awEntityFields[EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE] = $awEntityField;
        $extensionAttributes->setAwEntityFields($awEntityFields);
        $category->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category $subject
     * @param \Magento\Catalog\Model\ResourceModel\Category $result
     * @param CategoryInterface $category
     */
    public function afterDelete($subject, $result, $category)
    {
        $this->deleteHandler->delete($category->getId(), EntityFieldInterface::CATALOG_CATEGORY_ENTITY_TYPE);

        return $result;
    }
}
