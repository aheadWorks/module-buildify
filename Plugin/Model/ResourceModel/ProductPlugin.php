<?php
namespace Aheadworks\Buildify\Plugin\Model\ResourceModel;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\DeleteHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\SaveHandlerFactory;
use Aheadworks\Buildify\Model\Request\FormData\Processor as FormDataProcessor;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class ProductPlugin
 * @package Aheadworks\Buildify\Plugin\Model\ResourceModel
 */
class ProductPlugin
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
     * @var RequestInterface
     */
    private $request;

    /**
     * @var FormDataProcessor
     */
    private $formDataProcessor;

    /**
     * @var ProductExtensionFactory
     */
    private $extensionFactory;

    /**
     * @var DeleteHandler
     */
    private $deleteHandler;

    /**
     * @param SaveHandlerFactory $saveHandlerFactory
     * @param RequestInterface $request
     * @param FormDataProcessor $formDataProcessor
     * @param ProductExtensionFactory $extensionFactory
     * @param LoadHandler $loadHandler
     * @param DeleteHandler $deleteHandler
     */
    public function __construct(
        SaveHandlerFactory $saveHandlerFactory,
        RequestInterface $request,
        FormDataProcessor $formDataProcessor,
        ProductExtensionFactory $extensionFactory,
        LoadHandler $loadHandler,
        DeleteHandler $deleteHandler
    ) {
        $this->saveHandlerFactory = $saveHandlerFactory;
        $this->loadHandler = $loadHandler;
        $this->formDataProcessor = $formDataProcessor;
        $this->extensionFactory = $extensionFactory;
        $this->request = $request;
        $this->deleteHandler = $deleteHandler;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Page $subject
     * @param ProductInterface $product
     */
    public function beforeSave($subject, $product)
    {
        if (
            (!$this->request->getPostValue('extension_attributes') || $product->getIsDuplicate())
            && $product->getExtensionAttributes()
            && is_object($product->getExtensionAttributes())
            && $product->getExtensionAttributes()->getAwEntityFields()
        ) {
            return;
        }

        $entityDescField = $this->formDataProcessor->getEntityField(
            $this->request,
            EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE
        );

        $extensionAttributes = $product->getExtensionAttributes();
        if (!is_object($extensionAttributes)) {
            $extensionAttributes = $this->extensionFactory->create();
        }
        $awEntityFields = $extensionAttributes->getAwEntityFields() ?: [];
        $awEntityFields[EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE] = $entityDescField;

        $extensionAttributes->setAwEntityFields($awEntityFields);
        $product->setExtensionAttributes($extensionAttributes);
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Page $subject
     * @param callable $proceed
     * @param ProductInterface $product
     * @return \Magento\Cms\Model\ResourceModel\Page
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function aroundSave($subject, $proceed, $product)
    {
        $awEntityFields = $product->getExtensionAttributes()->getAwEntityFields();

        if (empty($awEntityFields)
            || (
                empty($awEntityFields[EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE]->getEditorConfig())
                && $product->getDescription() === null
            )
        ) {
            $result = $proceed($product);
        } else {
            //todo change logic to not save twice and add this logic to aroundSave category
            foreach ($awEntityFields as $awEntityField => $awEntityData) {
                $saveHandler = $this->saveHandlerFactory->create($awEntityField);
                $result = $saveHandler->save($product, $proceed);
            }
        }

        return $result;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product $subject
     * @param \Magento\Catalog\Model\ResourceModel\Product $result
     * @param ProductInterface $product
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterLoad($subject, $result, $product)
    {
        $extensionAttributes = $product->getExtensionAttributes();
        $awEntityFields = $extensionAttributes->getAwEntityFields() ?: [];

        $awEntityFields[EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE] = $this->loadHandler->load(
            $product->getId(),
            EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE
        );

        $extensionAttributes->setAwEntityFields($awEntityFields);

        $product->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product $subject
     * @param \Magento\Catalog\Model\ResourceModel\Product $result
     * @param ProductInterface $product
     */
    public function afterDelete($subject, $result, $product)
    {
        $this->deleteHandler->delete($product->getId(), EntityFieldInterface::CATALOG_PRODUCT_ENTITY_DESCRIPTION_TYPE);

        return $result;
    }
}
