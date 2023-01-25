<?php
namespace Aheadworks\Buildify\Controller\Page\Preview;

use Aheadworks\Buildify\Api\UrlManagementInterface;
use Aheadworks\Buildify\Model\Proxy\File\Management;
use Aheadworks\Buildify\Model\Proxy\File\UrlManagement;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class FileProxy
 * @package Aheadworks\Buildify\Controller\Page\Preview
 */
class FileProxy extends Action implements CsrfAwareActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var UrlManagementInterface
     */
    private $urlManagement;

    /**
     * @var UrlManagement
     */
    private $proxyUrlManagement;

    /**
     * @var Management
     */
    private $proxyManagement;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param UrlManagementInterface $urlManagement
     * @param UrlManagement $proxyUrlManagement
     * @param Management $proxyManagement
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        UrlManagementInterface $urlManagement,
        UrlManagement $proxyUrlManagement,
        Management $proxyManagement
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->urlManagement = $urlManagement;
        $this->proxyUrlManagement = $proxyUrlManagement;
        $this->proxyManagement = $proxyManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $hash = $this->getRequest()->getParam('hash');
        try {
            $fileUrl = $this->proxyUrlManagement->getFileUrl($hash);

            $fileContent = $this->proxyManagement->getContent($fileUrl);
            $contentType = $this->proxyManagement->getContentType($fileUrl);
        } catch (\Exception $e) {
            $fileContent = '';
            $contentType = 'text/html';
        }

        $resultRaw
            ->setContents($fileContent)
            ->setHeader('Content-Type', $contentType)
            ->setHeader('Access-Control-Allow-Origin', $this->urlManagement->getUrl('', [], false))
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->setHeader(
                'Access-Control-Allow-Headers',
                'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
            );

        return $resultRaw;
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
