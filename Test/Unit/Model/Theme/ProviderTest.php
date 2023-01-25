<?php
namespace Aheadworks\Buildify\Test\Unit\Model\Theme;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Buildify\Model\Theme\Provider;
use Aheadworks\Buildify\Model\ResourceModel\Design\Config\Grid\CollectionFactory;
use Aheadworks\Buildify\Model\ResourceModel\Design\Config\Grid\Collection;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;

/**
 * Class ProviderTest
 * @package Aheadworks\Buildify\Test\Unit\Model\Theme
 */
class ProviderTest extends TestCase
{
    /**
     * @var CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $themeCollectionFactoryMock;

    /**
     * @var Provider
     */
    private $provider;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->themeCollectionFactoryMock = $this->createPartialMock(CollectionFactory::class, ['create']);
        $this->provider = $objectManager->getObject(
            Provider::class,
            [
                'themeCollectionFactory' => $this->themeCollectionFactoryMock,
            ]
        );
    }

    /**
     * Test getThemes method
     */
    public function testGetThemes()
    {
        $expectedStoreName = 'test_store_mane';
        $expectedThemeId = '9999';
        $expected = [$expectedStoreName => $expectedThemeId];

        $collectionMock = $this->createPartialMock(Collection::class, ['getStoreThemes']);
        $itemMock = $this->createMock(Document::class);

        $this->themeCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($collectionMock);
        $collectionMock->expects($this->once())
            ->method('getStoreThemes')
            ->willReturn([$itemMock]);
        $itemMock->expects($this->exactly(2))
            ->method('__call')
            ->willReturnMap([
                ['getStoreName', [], $expectedStoreName],
                ['getThemeThemeId', [], $expectedThemeId]
            ]);

        $this->assertEquals($expected, $this->provider->getThemes());
    }
}