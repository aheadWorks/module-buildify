<?php
namespace Aheadworks\Buildify\Model\Service;

use Aheadworks\Buildify\Api\RequestManagementInterface;
use Aheadworks\Buildify\Api\UrlManagementInterface;
use Aheadworks\Buildify\Model\Api\Request\Curl;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Aheadworks\Buildify\Model\Config;
use Aheadworks\Buildify\Model\Theme\Provider as ThemeProvider;

/**
 * Class RequestService
 * @package Aheadworks\Buildify\Model\Service
 */
class RequestService implements RequestManagementInterface
{
    /**
     * @var Curl
     */
    private $curlRequest;

    /**
     * @var UrlManagementInterface
     */
    private $urlManagement;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ThemeProvider
     */
    private $themeProvider;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @param Curl $curlRequest
     * @param UrlManagementInterface $urlManagement
     * @param Json $serializer
     * @param Config $config
     * @param ThemeProvider $themeProvider
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        Curl $curlRequest,
        UrlManagementInterface $urlManagement,
        Json $serializer,
        Config $config,
        ThemeProvider $themeProvider,
        ImageHelper $imageHelper
    ) {
        $this->curlRequest = $curlRequest;
        $this->urlManagement = $urlManagement;
        $this->serializer = $serializer;
        $this->config = $config;
        $this->themeProvider = $themeProvider;
        $this->imageHelper = $imageHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        $params = [
            'public_api_key' => $this->config->getDecryptedPublicApiKey(),
            'private_api_key' => $this->config->getDecryptedPrivateApiKey()
        ];

        $url = $this->urlManagement->getUrl('token');

        return $this->curlRequest->request($url, $params, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function getFrameUrl()
    {
        return $this->urlManagement->getUrl('builder');
    }

    /**
     * {@inheritdoc}
     */
    public function getFrameUrlParams($builderConfig)
    {
        $object = base64_encode($this->serializer->serialize($builderConfig->getData()));
        $themes = base64_encode($this->serializer->serialize($this->themeProvider->getThemes()));
        $tokenResponse = $this->getToken();
        $token = $tokenResponse->getToken();

        return [
            'b_type' => 'pages',
            'b_id' => $builderConfig->getId(),
            'object' => $object,
            'theme' => $builderConfig->getCustomTheme(),
            'themes' => $themes,
            'buildify' => $token
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function processData($editorConfig)
    {
        $url = $this->urlManagement->getUrl('builder/act');
        $data = is_string($editorConfig->getEditorConfig())
            ? $editorConfig->getEditorConfig()
            : $this->serializer->serialize($editorConfig->getEditorConfig());

        $params = [
            'buildify' => $this->getToken()->getToken(),
            'action' => 'buildify_process_builder_data',
            'img_placeholder_link' => $this->imageHelper->getDefaultPlaceholderUrl('thumbnail'),
            'data' => $data
        ];

        return $this->curlRequest->request($url, $params, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function processEncodedData($encodedTemplateData)
    {
        $url = $this->urlManagement->getUrl('builder/act');

        $params = [
            'buildify' => $this->getToken()->getToken(),
            'action' => 'buildify_decode_process_builder_data',
            'img_placeholder_link' => $this->imageHelper->getDefaultPlaceholderUrl('thumbnail'),
            'encodedData' => $encodedTemplateData->getEncodedData()
        ];

        return $this->curlRequest->request($url, $params, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function encodeTemplateData($template)
    {
        $url = $this->urlManagement->getUrl('builder/act');

        $params = [
            'buildify' => $this->getToken()->getToken(),
            'action' => 'buildify_encode_template_data',
            'title' => $template->getTitle(),
            'source' => $template->getSource(),
            'type' => $template->getType(),
            'data' => $this->serializer->serialize($template->getEditorConfig()),
        ];

        return $this->curlRequest->request($url, $params, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function lightProcessData($editorConfig)
    {
        $url = $this->urlManagement->getUrl('builder/act');
        $params = [
            'buildify' => $this->getToken()->getToken(),
            'action' => 'buildify_light_process_builder_data',
            'object' => $editorConfig->getBuilderConfig() ? $editorConfig->getBuilderConfig()->getData() : null,
            'data' => $this->serializer->serialize($editorConfig->getEditorConfig()),
            'withHtmlContent' => $editorConfig->getIsWithHtmlContent(),
            'replaceElementIds' => $editorConfig->getIsReplaceElementIds()
        ];

        return $this->curlRequest->request($url, $params, 'POST');
    }
}
