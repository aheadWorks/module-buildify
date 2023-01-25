<?php
namespace Aheadworks\Buildify\Plugin\Model\ResourceModel;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\DeleteHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\SaveHandlerFactory;
use Magento\Cms\Api\Data\BlockInterface;

/**
 * Class BlockPlugin
 * @package Aheadworks\Buildify\Plugin\Model\ResourceModel
 */
class BlockPlugin
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
     * @param \Magento\Cms\Model\ResourceModel\Block $subject
     * @param callable $proceed
     * @param BlockInterface $block
     * @return \Magento\Cms\Model\ResourceModel\Block
     * @throws \Exception
     */
    public function aroundSave($subject, $proceed, $block)
    {
        if ($block->getAwEntityField()) {
            $saveHandler = $this->saveHandlerFactory->create(EntityFieldInterface::CMS_BLOCK_ENTITY_TYPE);
            $result = $saveHandler->save($block, $proceed);
        } else {
            $result = $proceed($block);
        }

        return $result;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Block $subject
     * @param \Magento\Cms\Model\ResourceModel\Block $result
     * @param BlockInterface $block
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterLoad($subject, $result, $block)
    {
        $awEntityField = $this->loadHandler->load($block->getId(), EntityFieldInterface::CMS_BLOCK_ENTITY_TYPE);
        $block->setAwEntityField($awEntityField);

        return $result;
    }

    /**
     * @param \Magento\Cms\Model\ResourceModel\Block $subject
     * @param \Magento\Cms\Model\ResourceModel\Block $result
     * @param BlockInterface $block
     */
    public function afterDelete($subject, $result, $block)
    {
        $this->deleteHandler->delete($block->getId(), EntityFieldInterface::CMS_BLOCK_ENTITY_TYPE);

        return $result;
    }
}
