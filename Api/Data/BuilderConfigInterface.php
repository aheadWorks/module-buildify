<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface BuilderConfigInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface BuilderConfigInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const ID = 'id';
    public const BODY_HTML = 'body_html';
    public const META_EDITOR_BODY_HTML = 'meta_editor_body_html';
    public const REVISION = 'revisions';
    public const LINK = 'link';
    public const IMG_PLACEHOLDER_LINK = 'img_placeholder_link';
    public const ENABLE_EXTERNAL_WIDGET_BUTTON = 'enable_external_widget_button';
    public const EXTERNAL_WIDGET_RENDER_LINK = 'external_widget_render_link';
    public const CUSTOM_THEME = 'custom_theme';
    /**#@-*/

    /**
     * Constant of default preview link
     */
    public const DEFAULT_PREVIEW_LINK = '/aw_buildify/page/preview';

    /**
     * Constant of default widget render link
     */
    public const DEFAULT_WIDGET_RENDER_LINK = 'aw_buildify/page/preview_widgetRender';

    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get body html
     *
     * @return string
     */
    public function getBodyHtml();

    /**
     * Set body html
     *
     * @param string $bodyHtml
     * @return $this
     */
    public function setBodyHtml($bodyHtml);

    /**
     * Get meta editor body html
     *
     * @return string
     */
    public function getMetaEditorBodyHtml();

    /**
     * Set meta editor body html
     *
     * @param string $metaEditorBodyHtml
     * @return $this
     */
    public function setMetaEditorBodyHtml($metaEditorBodyHtml);

    /**
     * Get revision
     *
     * @return array
     */
    public function getRevision();

    /**
     * Set revision
     *
     * @param array $revision
     * @return $this
     */
    public function setRevision($revision);

    /**
     * Get link
     *
     * @return string
     */
    public function getLink();

    /**
     * Set link
     *
     * @param string $link
     * @return $this
     */
    public function setLink($link);

    /**
     * Get img placeholder link
     *
     * @return string
     */
    public function getImgPlaceholderLink();

    /**
     * Set img placeholder link
     *
     * @param string $link
     * @return $this
     */
    public function setImgPlaceholderLink($link);

    /**
     * Get enable widget button
     *
     * @return bool
     */
    public function getEnableExternalWidgetButton();

    /**
     * Set enable widget button
     *
     * @param bool $enableWidgetButton
     * @return $this
     */
    public function setEnableExternalWidgetButton($enableWidgetButton);

    /**
     * Get external widget render link
     *
     * @return string
     */
    public function getExternalWidgetRenderLink();

    /**
     * Set external widget render link
     *
     * @param string $link
     * @return $this
     */
    public function setExternalWidgetRenderLink($link);

    /**
     * Get custom theme
     *
     * @return string
     */
    public function getCustomTheme();

    /**
     * Set custom theme
     *
     * @param string $theme
     * @return $this
     */
    public function setCustomTheme($theme);
}
