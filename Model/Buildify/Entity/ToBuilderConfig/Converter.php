<?php
namespace Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig;

use Aheadworks\Buildify\Api\Data\BuilderConfigInterface;
use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\Adapter\EntityAdapterInterface;
use Aheadworks\Buildify\ViewModel\Buildify\Builder\Revision\DataProvider as RevisionDataProvider;
use Aheadworks\Buildify\Api\Data\BuilderConfigInterfaceFactory;
use Magento\Catalog\Helper\Image as ImageHelper;
use Aheadworks\Buildify\Api\UrlManagementInterface;
use Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig\ConfigModifier;

/**
 * Class Converter
 * @package Aheadworks\Buildify\Model\Buildify\Entity\ToBuilderConfig
 */
class Converter
{
    /**
     * @var RevisionDataProvider
     */
    private $revisionDataProvider;

    /**
     * @var EntityAdapterInterface
     */
    private $entityAdapter;

    /**
     * @var BuilderConfigInterfaceFactory
     */
    private $builderConfigInterfaceFactory;

    /**
     * @var UrlManagementInterface
     */
    private $urlManagement;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var ConfigModifier
     */
    private $configModifier;

    /**
     * @param RevisionDataProvider $revisionDataProvider
     * @param EntityAdapterInterface $entityAdapter
     * @param BuilderConfigInterfaceFactory $builderConfigInterfaceFactory
     * @param UrlManagementInterface $urlManagement
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        RevisionDataProvider $revisionDataProvider,
        EntityAdapterInterface $entityAdapter,
        BuilderConfigInterfaceFactory $builderConfigInterfaceFactory,
        UrlManagementInterface $urlManagement,
        ImageHelper $imageHelper,
        ConfigModifier $configModifier
    ) {
        $this->revisionDataProvider = $revisionDataProvider;
        $this->entityAdapter = $entityAdapter;
        $this->builderConfigInterfaceFactory = $builderConfigInterfaceFactory;
        $this->urlManagement = $urlManagement;
        $this->imageHelper = $imageHelper;
        $this->configModifier = $configModifier;
    }

    /**
     * Prepare data
     *
     * @param mixed $entity
     * @return BuilderConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function convert($entity)
    {
        /** @var EntityFieldInterface $awBfyEntity */
        $awBfyEntity = $this->entityAdapter->getEntityField($entity);
        $awBfyEntityId = $awBfyEntity && $awBfyEntity->getId() ? $awBfyEntity->getId() : null;
        if($awBfyEntity) {
            $awBfyEntity = $this->configModifier->modifyLinkEntityField($awBfyEntity);
        }
        $revisions = $awBfyEntityId
            ? $this->revisionDataProvider->getData($awBfyEntityId)
            : [];
        $customTheme = $this->entityAdapter->getCustomTheme($entity);

        /** @var BuilderConfigInterface $builderConfig */
        $builderConfig = $this->builderConfigInterfaceFactory->create();
        $builderConfig
            ->setId($awBfyEntityId ?: uniqid())
            ->setBodyHtml(
                $entity && $this->entityAdapter->getHtml($entity)
                    ? $this->configModifier->modifyLinkContent($this->entityAdapter->getHtml($entity))
                    : '<div id="buildify" class="buildify"></div>'
            )
            ->setMetaEditorBodyHtml(
                $awBfyEntity ? $awBfyEntity->getEditorConfig() : ''
            )
            ->setRevision($revisions)
            ->setLink(BuilderConfigInterface::DEFAULT_PREVIEW_LINK)
            ->setImgPlaceholderLink($this->imageHelper->getDefaultPlaceholderUrl('thumbnail'))
            ->setEnableExternalWidgetButton(true)
            ->setExternalWidgetRenderLink(
                $this->urlManagement->getFrontendUrl(BuilderConfigInterface::DEFAULT_WIDGET_RENDER_LINK)
            )
            ->setCustomTheme($customTheme);

        return $builderConfig;
    }
}
