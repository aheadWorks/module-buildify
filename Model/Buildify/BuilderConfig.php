<?php
namespace Aheadworks\Buildify\Model\Buildify;

use Aheadworks\Buildify\Api\Data\BuilderConfigInterface;
use Magento\Framework\DataObject;

/**
 * Class BuilderConfig
 * @package Aheadworks\Buildify\Model\Buildify
 */
class BuilderConfig extends DataObject implements BuilderConfigInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getBodyHtml()
    {
        return $this->getData(self::BODY_HTML);
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyHtml($bodyHtml)
    {
        return $this->setData(self::BODY_HTML, $bodyHtml);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaEditorBodyHtml()
    {
        return $this->getData(self::META_EDITOR_BODY_HTML);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaEditorBodyHtml($metaEditorBodyHtml)
    {
        return $this->setData(self::META_EDITOR_BODY_HTML, $metaEditorBodyHtml);
    }

    /**
     * {@inheritdoc}
     */
    public function getRevision()
    {
        return $this->getData(self::REVISION);
    }

    /**
     * {@inheritdoc}
     */
    public function setRevision($revision)
    {
        return $this->setData(self::REVISION, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return $this->getData(self::LINK);
    }

    /**
     * {@inheritdoc}
     */
    public function setLink($link)
    {
        return $this->setData(self::LINK, $link);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgPlaceholderLink()
    {
        return $this->getData(self::IMG_PLACEHOLDER_LINK);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgPlaceholderLink($link)
    {
        return $this->setData(self::IMG_PLACEHOLDER_LINK, $link);
    }

    /**
     * {@inheritdoc}
     */
    public function getEnableExternalWidgetButton()
    {
        return $this->getData(self::ENABLE_EXTERNAL_WIDGET_BUTTON);
    }

    /**
     * {@inheritdoc}
     */
    public function setEnableExternalWidgetButton($enableWidgetButton)
    {
        return $this->setData(self::ENABLE_EXTERNAL_WIDGET_BUTTON, $enableWidgetButton);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalWidgetRenderLink()
    {
        return $this->getData(self::EXTERNAL_WIDGET_RENDER_LINK);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalWidgetRenderLink($link)
    {
        return $this->setData(self::EXTERNAL_WIDGET_RENDER_LINK, $link);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomTheme()
    {
        return $this->getData(self::CUSTOM_THEME);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomTheme($theme)
    {
        return $this->setData(self::CUSTOM_THEME, $theme);
    }
}
