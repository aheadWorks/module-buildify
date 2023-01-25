<?php
namespace Aheadworks\Buildify\Plugin\Model\ResourceModel;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\DeleteHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\SaveHandlerFactory;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\Data\PageExtensionFactory;

/**
 * Class PagePlugin
 * @package Aheadworks\Buildify\Plugin\Model\ResourceModel
 */
class PagePlugin
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
     * @var ProductExtensionFactory
     */
    private $extensionFactory;

    /**
     * @param SaveHandlerFactory $saveHandlerFactory
     * @param LoadHandler $loadHandler
     * @param DeleteHandler $deleteHandler
     * @param ProductExtensionFactory $extensionFactory
     */
    public function __construct(
        SaveHandlerFactory $saveHandlerFactory,
        LoadHandler $loadHandler,
        DeleteHandler $deleteHandler,
        ProductExtensionFactory $extensionFactory
    ) {
        $this->saveHandlerFactory = $saveHandlerFactory;
        $this->loadHandler = $loadHandler;
        $this->deleteHandler = $deleteHandler;
        $this->extensionFactory = $extensionFactory;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Page $subject
     * @param callable $proceed
     * @param PageInterface $page
     * @return \Magento\Cms\Model\ResourceModel\Page
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function aroundSave($subject, $proceed, $page)
    {
        if ($page->getAwEntityField()) {
            $saveHandler = $this->saveHandlerFactory->create(EntityFieldInterface::CMS_PAGE_ENTITY_TYPE);
            $result = $saveHandler->save($page, $proceed);
        } else {
            $result = $proceed($page);
        }

        return $result;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Page $subject
     * @param \Magento\Cms\Model\ResourceModel\Page $result
     * @param PageInterface $page
     */
    public function afterLoad($subject, $result, $page)
    {
        $awEntityField = $this->loadHandler->load($page->getId(), EntityFieldInterface::CMS_PAGE_ENTITY_TYPE);
        $page->setAwEntityField($awEntityField);

        $extensionAttributes = $page->getExtensionAttributes();
        if (!is_object($extensionAttributes)) {
            $extensionAttributes = $this->extensionFactory->create();
        }
        $awEntityFields = $extensionAttributes->getAwEntityFields() ?: [];
        $awEntityFields[EntityFieldInterface::CMS_PAGE_ENTITY_TYPE] = $awEntityField;

        $extensionAttributes->setAwEntityFields($awEntityFields);
        $page->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Page $subject
     * @param \Magento\Cms\Model\ResourceModel\Page $result
     * @param PageInterface $page
     */
    public function afterDelete($subject, $result, $page)
    {
        $this->deleteHandler->delete($page->getId(), EntityFieldInterface::CMS_PAGE_ENTITY_TYPE);

        return $result;
    }
}
