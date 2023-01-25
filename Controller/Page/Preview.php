<?php
namespace Aheadworks\Buildify\Controller\Page;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\DesignInterface;

/**
 * Class Preview
 * @package Aheadworks\Buildify\Controller\Page
 */
class Preview extends Action
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var PageFactory
     */
    private $design;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param DesignInterface $design
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        DesignInterface $design
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->design = $design;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        if ($theme = $this->getRequest()->getParam('theme')) {
            $this->design->setDesignTheme($theme);
        }

        $resultPage->getConfig()->getTitle()->set(__('Preview'));

        return $resultPage;
    }
}
