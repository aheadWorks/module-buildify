<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface CommonEntityInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface CommonEntityInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const CSS_STYLE = 'css_style';
    public const EDITOR_CONFIG = 'editor_config';
    /**#@-*/

    /**
     * Get css style
     *
     * @return string
     */
    public function getCssStyle();

    /**
     * Set css style
     *
     * @param string $cssStyle
     * @return $this
     */
    public function setCssStyle($cssStyle);

    /**
     * Get editor config
     *
     * @return string[]
     */
    public function getEditorConfig();

    /**
     * Set editor config
     *
     * @param string[] $editorConfig
     * @return $this
     */
    public function setEditorConfig($editorConfig);
}
