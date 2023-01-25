<?php
namespace Aheadworks\Buildify\Test\Unit\Model\Service;

use Aheadworks\Buildify\Model\VersionChecker;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Url;
use Aheadworks\Buildify\Model\Service\UrlService;
use Magento\Store\Model\Store;
use Aheadworks\Buildify\Model\Config;

/**
 * Class UrlServiceTest
 * @package Aheadworks\Buildify\Test\Unit\Model\Service
 */
class UrlServiceTest extends TestCase
{
    /**
     * @var StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $configMock;

    /**
     * @var Url|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlMock;

    /**
     * @var VersionChecker|\PHPUnit\Framework\MockObject\MockObject
     */
    private $versionCheckerMock;

    /**
     * @var UrlService
     */
    private $urlService;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->scopeConfigMock = $this->getMockForAbstractClass(ScopeConfigInterface::class);
        $this->configMock = $this->createPartialMock(Config::class, ['getApiBaseUrl']);
        $this->versionCheckerMock = $this->createPartialMock(VersionChecker::class, ['getModuleVersion']);
        $this->urlMock = $this->createPartialMock(
            Url::class,
            ['getUrl']
        );

        $this->urlService = $objectManager->getObject(
            UrlService::class,
            [
                'storeManager' => $this->storeManagerMock,
                'frontendUrl' => $this->urlMock,
                'versionChecker' => $this->versionCheckerMock,
                'scopeConfig' => $this->scopeConfigMock,
                'config' => $this->configMock
            ]
        );
    }

    /**
     * Test getUrl method
     *
     * @dataProvider dataProviderGetUrlTest
     */
    public function testGetUrl($routeBaseApiUrl, $routePath, $params, $addAdditional, $isSecure, $testShopUrl, $version, $expectedPath)
    {
        $storeMock = $this->urlMock = $this->createPartialMock(
            Store::class,
            ['getBaseUrl']
        );

        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($storeMock);
        $this->scopeConfigMock->expects($this->any())
            ->method('isSetFlag')
            ->willReturn($isSecure);
        $this->versionCheckerMock->expects($this->any())
            ->method('getModuleVersion')
            ->willReturn($version);
        $this->configMock->expects($this->once())
            ->method('getApiBaseUrl')
            ->willReturn($routeBaseApiUrl);
        $storeMock->expects($this->any())
            ->method('getBaseUrl')
            ->with('link', $isSecure)
            ->willReturn($testShopUrl);

        $expected = $routeBaseApiUrl . $expectedPath;
        $this->assertEquals($expected, $this->urlService->getUrl($routePath, $params, $addAdditional));
    }

    /**
     * Test getPath method
     */
    public function testGetPath()
    {
        $url = 'https://example.com/test_path?arg=value#anchor';
        $expected = '/test_path';

        $this->assertEquals($expected, $this->urlService->getPath($url));
    }

    /**
     * Test getFrontendUrl method
     */
    public function testGetFrontendUrl()
    {
        $path = 'test_path';
        $expected = 'https://example.com/test_path';

        $this->urlMock->expects($this->once())
            ->method('getUrl')
            ->with()
            ->willReturn($expected);

        $this->assertEquals($expected, $this->urlService->getFrontendUrl($path, []));
    }

    /**
     * Retrieve data provider for testGetUrl test
     *
     * @return array
     */
    public function dataProviderGetUrlTest()
    {
        return [
            [
                'https://magento.buildify.shop',
                'test_route_path',
                ['test_key' => 'test_value'],
                true,
                true,
                'https://example.com',
                '1.0.0',
                '/test_route_path?test_key=test_value&platform=' . UrlService::PLATFORM
                    . '&shop=https%3A%2F%2Fexample.com&version=1.0.0'
            ],
            [
                'https://magento.buildify.shop',
                'test_route_path',
                ['test_key' => 'test_value'],
                true,
                false,
                'http://example.com',
                '1.0.0',
                '/test_route_path?test_key=test_value&platform=' . UrlService::PLATFORM
                . '&shop=http%3A%2F%2Fexample.com&version=1.0.0'
            ],
            [
                'https://magento.buildify.shop',
                'test_route_path',
                ['test_key' => 'test_value'],
                false,
                false,
                'http://example.com',
                '1.0.0',
                ''
            ],
        ];
    }
}