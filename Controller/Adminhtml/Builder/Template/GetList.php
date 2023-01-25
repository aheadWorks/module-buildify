<?php
namespace Aheadworks\Buildify\Controller\Adminhtml\Builder\Template;

use Aheadworks\Buildify\ViewModel\Buildify\Builder\Template\DataProvider;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class GetList
 * @package Aheadworks\Buildify\Controller\Adminhtml\Builder\Template
 */
class GetList extends BackendAction
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param DataProvider $dataProvider
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        DataProvider $dataProvider
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->dataProvider = $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];
        try {
            $content = $this->dataProvider->getData();

            $result = ['error' => false, 'content' => $content];
        } catch (LocalizedException $e) {
            $result = ['error' => true, 'message' => __($e->getMessage())];
        } catch (\Exception $e) {
            $result = ['error' => true, 'message' => __($e->getMessage())];
        }

        return $resultJson->setData($result);
    }
}
