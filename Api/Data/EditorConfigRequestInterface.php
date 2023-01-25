<?php
namespace Aheadworks\Buildify\Api\Data;

/**
 * Interface EditorConfigRequestInterface
 * @package Aheadworks\Buildify\Api\Data
 */
interface EditorConfigRequestInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const EDITOR_CONFIG = 'editor_config';
    const BUILDER_CONFIG = 'builder_config';
    const IS_WITH_HTML_CONTENT = 'with_html_content';
    const IS_REPLACE_ELEMENT_IDS = 'replace_element_ids';
    const ENCODED_DATA = 'encoded_data';
    /**#@-*/

    /**
     * Get editor config
     *
     * @return array
     */
    public function getEditorConfig();

    /**
     * Set editor config
     *
     * @param array $editorConfig
     * @return $this
     */
    public function setEditorConfig($editorConfig);

    /**
     * Get builder config
     *
     * @return \Aheadworks\Buildify\Api\Data\BuilderConfigInterface
     */
    public function getBuilderConfig();

    /**
     * Set builder config
     *
     * @param \Aheadworks\Buildify\Api\Data\BuilderConfigInterface $config
     * @return $this
     */
    public function setBuilderConfig($config);

    /**
     * Get is with html content
     *
     * @return bool
     */
    public function getIsWithHtmlContent();

    /**
     * Set is with html content
     *
     * @param bool $isWithHtmlContent
     * @return $this
     */
    public function setIsWithHtmlContent($isWithHtmlContent);

    /**
     * Get is replace element ids
     *
     * @return bool
     */
    public function getIsReplaceElementIds();

    /**
     * Set is replace element ids
     *
     * @param bool $isReplaceElementIds
     * @return $this
     */
    public function setIsReplaceElementIds($isReplaceElementIds);

    /**
     * Get editor config
     *
     * @return array
     */
    public function getEncodedData();

    /**
     * Set editor config
     *
     * @param string $encodedData
     * @return $this
     */
    public function setEncodedData($encodedData);
}
