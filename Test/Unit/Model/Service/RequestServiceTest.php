<?php
namespace Aheadworks\Buildify\Test\Unit\Model\Service;

use Aheadworks\Buildify\Model\Api\Request\Curl;
use Aheadworks\Buildify\Model\Config;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Buildify\Model\Theme\Provider as ThemeProvider;
use Aheadworks\Buildify\Model\Service\UrlService;
use Aheadworks\Buildify\Model\Service\RequestService;
use PHPUnit\Framework\TestCase;
use Aheadworks\Buildify\Model\Buildify\BuilderConfig;
use Aheadworks\Buildify\Model\Api\Result\Response;
use Aheadworks\Buildify\Api\Data\EditorConfigRequestInterface;
use \Magento\Catalog\Helper\Image;

/**
 * Class RequestServiceTest
 * @package Aheadworks\Buildify\Test\Unit\Model\Service
 */
class RequestServiceTest extends TestCase
{
    /**
     * @var Curl|\PHPUnit\Framework\MockObject\MockObject
     */
    private $curlRequestMock;

    /**
     * @var UrlService|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlManagementMock;

    /**
     * @var Json|\PHPUnit\Framework\MockObject\MockObject
     */
    private $serializerMock;

    /**
     * @var Config|\PHPUnit\Framework\MockObject\MockObject
     */
    private $configMock;

    /**
     * @var ThemeProvider|\PHPUnit\Framework\MockObject\MockObject
     */
    private $themeProviderMock;

    /**
     * @var Image|\PHPUnit\Framework\MockObject\MockObject
     */
    private $imageHelperMock;

    /**
     * @var Response|\PHPUnit\Framework\MockObject\MockObject
     */
    private $responseMock;

    /**
     * @var RequestService
     */
    private $requestService;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->curlRequestMock = $this->createPartialMock(
            Curl::class,
            ['request']
        );
        $this->urlManagementMock = $this->createPartialMock(
            UrlService::class,
            ['getUrl']);
        $this->serializerMock = $this->createPartialMock(
            Json::class,
            ['serialize']
        );
        $this->configMock = $this->createPartialMock(
            Config::class,
            ['getDecryptedPublicApiKey', 'getDecryptedPrivateApiKey']
        );
        $this->themeProviderMock = $this->createPartialMock(
            ThemeProvider::class,
            ['getThemes']
        );
        $this->responseMock = $this->createPartialMock(
            Response::class,
            ['__call']
        );
        $this->imageHelperMock = $this->createPartialMock(
            Image::class,
            ['getDefaultPlaceholderUrl']
        );
        $this->requestService = $objectManager->getObject(
            RequestService::class,
            [
                'curlRequest' => $this->curlRequestMock,
                'urlManagement' => $this->urlManagementMock,
                'serializer' => $this->serializerMock,
                'config' => $this->configMock,
                'themeProvider' => $this->themeProviderMock,
                'imageHelper' => $this->imageHelperMock
            ]
        );
    }

    /**
     * Test getToken method
     */
    public function testGetToken()
    {
        $this->prepareGetTokenTest();

        $this->assertEquals($this->responseMock, $this->requestService->getToken());
    }

    /**
     * Test getFrameUrl method
     */
    public function testgetFrameUrl()
    {
        $expected = 'https://example.com/builder';

        $this->urlManagementMock->expects($this->once())
            ->method('getUrl')
            ->with('builder')
            ->willReturn($expected);

        $this->assertEquals($expected, $this->requestService->getFrameUrl());
    }

    /**
     * Test getFrameUrlParams method
     */
    public function testGetFrameUrlParams()
    {
        $builderConfigId = '1';
        $token = 'test_token';
        $testData = ['test_key' => 'test_value'];
        $testTheme = '9999';
        $serializedTestData = '{"test_key":"test_value"}';
        $expected = [
            'b_type' => 'pages',
            'b_id' => $builderConfigId,
            'object' => base64_encode($serializedTestData),
            'theme' => $testTheme,
            'themes' => base64_encode($serializedTestData),
            'buildify' => $token
        ];

        $this->prepareGetTokenTest();
        $this->responseMock->expects($this->once())
            ->method('__call')
            ->with('getToken')
            ->willReturn($token);
        $builderConfigMock = $this->createPartialMock(
            BuilderConfig::class,
            ['getData', 'getId', 'getCustomTheme']
        );
        $builderConfigMock->expects($this->once())
            ->method('getData')
            ->willReturn($testData);
        $builderConfigMock->expects($this->once())
            ->method('getId')
            ->willReturn($builderConfigId);
        $builderConfigMock->expects($this->once())
            ->method('getCustomTheme')
            ->willReturn($testTheme);
        $this->themeProviderMock->expects($this->once())
            ->method('getThemes')
            ->willReturn($testData);

        $this->serializerMock->expects($this->exactly(2))
            ->method('serialize')
            ->with($testData)
            ->willReturn($serializedTestData);


        $this->assertEquals($expected, $this->requestService->getFrameUrlParams($builderConfigMock));
    }

    /**
     * Test processData method
     *
     * @dataProvider dataProviderProcessDataTest
     * @param string|array $editorConfigData
     */
    public function testProcessData($editorConfigData)
    {
        $serializedEditorConfigData = '{"test_key":"test_value"}';
        $token = 'test_token';
        $imgPlaceholderLink = 'https://example.com/builder/img_placeholder_link';
        $params = [
            'buildify' => $token,
            'action' => 'buildify_process_builder_data',
            'img_placeholder_link' => $imgPlaceholderLink,
            'data' => $serializedEditorConfigData
        ];
        $builderActUrl = 'https://example.com/builder/act';

        $editorConfigMock = $this->getMockForAbstractClass(EditorConfigRequestInterface::class);
        $editorConfigMock->expects($this->atLeastOnce())
            ->method('getEditorConfig')
            ->willReturn($editorConfigData);
        $this->serializerMock->expects($this->any())
            ->method('serialize')
            ->with($editorConfigData)
            ->willReturn($serializedEditorConfigData);
        $this->prepareGetTokenTest();
        $this->responseMock->expects($this->once())
            ->method('__call')
            ->with('getToken')
            ->willReturn($token);
        $this->imageHelperMock->expects($this->once())
            ->method('getDefaultPlaceholderUrl')
            ->willReturn($imgPlaceholderLink);
        $this->curlRequestMock->expects($this->at(1))
            ->method('request')
            ->with($builderActUrl, $params, 'POST')
            ->willReturn($this->responseMock);

        $this->assertEquals($this->responseMock, $this->requestService->processData($editorConfigMock));
    }

    /**
     * Test lightProcessData method
     *
     * @dataProvider dataProviderLightProcessDataTest
     * @param boolean $isbuilderConfigSet
     * @param string|null $builderConfigData
     * @param array $editorConfigData
     * @param string $serializedEditorConfigData
     * @param string $withHtmlContent
     * @param string $replaceElementIds
     */
    public function testLightProcessData(
        $isbuilderConfigSet,
        $builderConfigData,
        $editorConfigData,
        $serializedEditorConfigData,
        $withHtmlContent,
        $replaceElementIds
    ) {
        $token = 'test_token';
        $params = [
            'buildify' => $token,
            'action' => 'buildify_light_process_builder_data',
            'object' => $builderConfigData,
            'data' => $serializedEditorConfigData,
            'withHtmlContent' => $withHtmlContent,
            'replaceElementIds' => $replaceElementIds
        ];
        $builderActUrl = 'https://example.com/builder/act';

        $builderConfigMock = null;
        if ($isbuilderConfigSet) {
            $builderConfigMock = $this->createPartialMock(BuilderConfig::class, ['getData']);
            $builderConfigMock->expects($this->any())
                ->method('getData')
                ->willReturn($builderConfigData);
        }

        $editorConfigMock = $this->getMockForAbstractClass(EditorConfigRequestInterface::class);
        $editorConfigMock->expects($this->atLeastOnce())
            ->method('getBuilderConfig')
            ->willReturn($builderConfigMock);
        $editorConfigMock->expects($this->once())
            ->method('getEditorConfig')
            ->willReturn($editorConfigData);
        $editorConfigMock->expects($this->once())
            ->method('getIsWithHtmlContent')
            ->willReturn($withHtmlContent);
        $editorConfigMock->expects($this->once())
            ->method('getIsReplaceElementIds')
            ->willReturn($replaceElementIds);
        $this->serializerMock->expects($this->once())
            ->method('serialize')
            ->with($editorConfigData)
            ->willReturn($serializedEditorConfigData);
        $this->prepareGetTokenTest();
        $this->responseMock->expects($this->once())
            ->method('__call')
            ->with('getToken')
            ->willReturn($token);
        $this->curlRequestMock->expects($this->at(1))
            ->method('request')
            ->with($builderActUrl, $params, 'POST')
            ->willReturn($this->responseMock);

        $this->assertEquals($this->responseMock, $this->requestService->lightProcessData($editorConfigMock));
    }

    /**
     * Retrieve data provider for testEncrypt test
     *
     * @return array
     */
    public function dataProviderProcessDataTest()
    {
        return [
            [['test_key' => 'test_value']],
            ['{"test_key":"test_value"}']
        ];
    }

    /**
     * Retrieve data provider for testEncrypt test
     *
     * @return array
     */
    public function dataProviderLightProcessDataTest()
    {
        return [
            [
                true,
                'test_builder_config_data',
                ['test_key' => 'test_value'],
                '{"test_key":"test_value"}',
                'test_with_html_content',
                'replace_element_ids'
            ],
            [
                true,
                null,
                ['test_key' => 'test_value'],
                '{"test_key":"test_value"}',
                'test_with_html_content',
                'replace_element_ids'
            ],
            [
                false,
                null,
                ['test_key' => 'test_value'],
                '{"test_key":"test_value"}',
                'test_with_html_content',
                'replace_element_ids'
            ]
        ];
    }

    private function prepareGetTokenTest()
    {
        $params = [
            'public_api_key' => 'test_public_api_key',
            'private_api_key' => 'test_private_api_key'
        ];
        $tokenUrl = 'https://example.com/token';
        $builderActUrl = 'https://example.com/builder/act';

        $this->configMock->expects($this->once())
            ->method('getDecryptedPublicApiKey')
            ->willReturn($params['public_api_key']);
        $this->configMock->expects($this->once())
            ->method('getDecryptedPrivateApiKey')
            ->willReturn($params['private_api_key']);
        $this->urlManagementMock->expects($this->atLeastOnce())
            ->method('getUrl')
            ->willReturnMap([
                ['token', [], true, $tokenUrl],
                ['builder/act', [], true, $builderActUrl]
            ]);
        $this->curlRequestMock->expects($this->at(0))
            ->method('request')
            ->with($tokenUrl, $params, 'POST')
            ->willReturn($this->responseMock);
    }
}