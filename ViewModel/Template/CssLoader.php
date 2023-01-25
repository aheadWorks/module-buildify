<?php
namespace Aheadworks\Buildify\ViewModel\Template;

use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Cms\Api\GetPageByIdentifierInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Helper\Page;

/**
 * Class CssLoader
 * @package Aheadworks\Buildify\ViewModel\Template
 */
class CssLoader
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * @var array
     */
    private $identifiers;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var GetPageByIdentifierInterface
     */
    private $pageByIdentifier;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param RequestInterface $request
     * @param LoadHandler $loadHandler
     * @param ScopeConfigInterface $scopeConfig
     * @param GetPageByIdentifierInterface $getPageByIdentifier
     * @param StoreManagerInterface $storeManager
     * @param array $identifiers
     */
    public function __construct(
        RequestInterface $request,
        LoadHandler $loadHandler,
        ScopeConfigInterface $scopeConfig,
        GetPageByIdentifierInterface $getPageByIdentifier,
        StoreManagerInterface $storeManager,
        array $identifiers
    ) {
        $this->request = $request;
        $this->loadHandler = $loadHandler;
        $this->scopeConfig = $scopeConfig;
        $this->pageByIdentifier = $getPageByIdentifier;
        $this->storeManager = $storeManager;
        $this->identifiers = $identifiers;

    }

    /**
     * Retrieve entity css
     *
     * @return string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCssStyle()
    {
        $entityFieldKey = $this->request->getModuleName() . '_' . $this->request->getControllerName();

        if (!isset($this->identifiers[$entityFieldKey])) {
            return '';
        }

        $entityFieldData = $this->identifiers[$entityFieldKey];
        $pageId = $this->getPageId($entityFieldKey, $entityFieldData);
        $entity = $this->loadHandler->load($pageId, $entityFieldData['entityType']);

        return $entity->getCssStyle();
    }

    /**
     * Retrieve entity id
     *
     * @param string $entityFieldKey
     * @param array $entityFieldData
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPageId($entityFieldKey, $entityFieldData)
    {
        if ($entityFieldKey == 'cms_index') {
            $homePageIdentifier = $this->scopeConfig->getValue(
                Page::XML_PATH_HOME_PAGE,
                ScopeInterface::SCOPE_STORE
            );
            $page = $this->pageByIdentifier->execute($homePageIdentifier, $this->storeManager->getStore()->getId());
            $entityId = $page->getId();
        } else {
            $entityId = (int)$this->request->getParam($entityFieldData['requestKey']);
        }

        return $entityId;
    }
}