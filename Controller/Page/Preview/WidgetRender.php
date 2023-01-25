<?php
namespace Aheadworks\Buildify\Controller\Page\Preview;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Widget\Model\Template\Filter as WidgetFilter;
use Magento\Framework\App\RequestInterface;

/**
 * Class WidgetRender
 * @package Aheadworks\Buildify\Controller\Page\Preview
 */
class WidgetRender extends Action implements CsrfAwareActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var WidgetFilter
     */
    private $widgetFilter;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param WidgetFilter $widgetFilter
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        WidgetFilter $widgetFilter
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->widgetFilter = $widgetFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = ['error' => true, 'message' => __('Invalid response data.')];

        $data = $this->getRequest()->getPostValue();
        if (is_array($data) && isset($data['content'])) {
            try {
                $content = $this->widgetFilter->filter($data['content']);

                $result = ['error' => false, 'content' => $content];
            } catch (LocalizedException $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            } catch (\Exception $e) {
                $result = ['error' => true, 'message' => __($e->getMessage())];
            }
        }

        return $resultJson->setData($result);
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * Disable Magento's CSRF validation.
     *
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
