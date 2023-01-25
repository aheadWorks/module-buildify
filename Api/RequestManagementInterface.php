<?php
namespace Aheadworks\Buildify\Api;

/**
 * Interface RequestManagementInterface
 * @package Aheadworks\Buildify\Api
 */
interface RequestManagementInterface
{
    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken();

    /**
     * Retrieve frame url
     *
     * @return string
     */
    public function getFrameUrl();

    /**
     * Retrieve frame url params
     *
     * @param \Aheadworks\Buildify\Api\Data\BuilderConfigInterface $builderConfig
     * @return string
     */
    public function getFrameUrlParams($builderConfig);

    /**
     * Process data before save
     *
     * @param \Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface $editorConfig
     * @return mixed
     */
    public function processData($editorConfig);

    /**
     * Decode and process data before save
     *
     * @param \Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface $encodedTemplateData
     * @return \Aheadworks\Buildify\Api\Data\TemplateInterface[]
     */
    public function processEncodedData($encodedTemplateData);

    /**
     * Encode template data
     *
     * @param \Aheadworks\Buildify\Api\Data\TemplateInterface $template
     * @return mixed
     */
    public function encodeTemplateData($template);

    /**
     * Process data before import data to editor
     *
     * @param \Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface $editorConfig
     * @return mixed
     */
    public function lightProcessData($editorConfig);
}
