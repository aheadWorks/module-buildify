<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface EditorConfigResponseInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface EditorConfigResponseInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    public const HTML = 'html';
    public const STYLE = 'style';
    public const EDITOR = 'editor';
    public const ENCODED_CONTENT = 'encoded_content';
    public const EXCEPTION_MESSAGE = 'exception_message';
    /**#@-*/

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml();

    /**
     * Set html
     *
     * @param string $html
     * @return $this
     */
    public function setHtml($html);

    /**
     * Get style
     *
     * @return string
     */
    public function getStyle();

    /**
     * Set css style
     *
     * @param string $cssStyle
     * @return $this
     */
    public function setStyle($cssStyle);

    /**
     * Get editor config
     *
     * @return string[]
     */
    public function getEditor();

    /**
     * Set editor config
     *
     * @param string[] $editorConfig
     * @return $this
     */
    public function setEditor($editorConfig);

    /**
     * Get encoded content
     *
     * @return string
     */
    public function getEncodedContent();

    /**
     * Set encoded content
     *
     * @param string $encodedContent
     * @return $this
     */
    public function setEncodedContent($encodedContent);

    /**
     * Get exception message
     *
     * @return string
     */
    public function getExceptionMessage();

    /**
     * Set exception message
     *
     * @param string $exceptionMessage
     * @return $this
     */
    public function setExceptionMessage($exceptionMessage);
}