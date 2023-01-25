<?php
namespace Aheadworks\Buildify\Block\Adminhtml\Buildify;

use Magento\Framework\View\Element\Template;
use Aheadworks\Buildify\ViewModel\Buildify\Builder as BuilderViewModel;

/**
 * Class Builder
 * @package Aheadworks\Buildify\Block\Adminhtml\Buildify
 */
class Builder extends Template
{
    /**
     * @var BuilderViewModel
     */
    private $viewModel;

    /**
     * @param Template\Context $context
     * @param BuilderViewModel $viewModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        BuilderViewModel $viewModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->viewModel = $viewModel;
    }

    /**
     * Retrieve view model
     *
     * @return BuilderViewModel
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $extensionAttributesKey = $this->getExtensionAttributesKey();
        $frameUrlParamsJson = $this->getViewModel()->getFrameUrlParamsJson($extensionAttributesKey);
        if (isset($frameUrlParamsJson)) {
            $this->setFrameUrlParamsJson($frameUrlParamsJson);
        } else {
            $this->setTemplate('Aheadworks_Buildify::buildify/builder/error_message.phtml');
        }

        return parent::toHtml();
    }
}
