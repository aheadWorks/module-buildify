<?php
namespace Aheadworks\Buildify\Test\Unit\Model;

use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Buildify\Model\VersionChecker;

/**
 * Class VersionCheckerTest
 * @package Aheadworks\Buildify\Test\Unit\Model
 */
class VersionCheckerTest extends TestCase
{
    /**
     * @var ComponentRegistrarInterface
     */
    private $componentRegistrar;

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * @var ReadInterface
     */
    private $read;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var VersionChecker
     */
    private $versionChecker;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->json = $this->createMock(Json::class);
        $this->read = $this->createMock(ReadInterface::class);
        $this->readFactory = $this->createMock(ReadFactory::class);
        $this->componentRegistrar = $this->createMock(ComponentRegistrarInterface::class);
        $this->versionChecker = $objectManager->getObject(
            VersionChecker::class,
            [
                'componentRegistrar' => $this->componentRegistrar,
                'readFactory' => $this->readFactory,
                'json' => $this->json
            ]
        );
    }

    /**
     * Test getModuleVersion method
     */
    public function testGetModuleVersion()
    {
        $version = '1.0.0';
        $moduleInfo = ['version' => $version];
        $this->readFactory->expects($this->once())
                          ->method('create')
                          ->willReturn($this->read);

        $this->read->expects($this->once())
                   ->method('readFile')
                   ->with('composer.json')
                   ->willReturn(json_encode($moduleInfo));

        $this->json->expects($this->once())
                   ->method('unserialize')
                   ->with(json_encode($moduleInfo))
                   ->willReturn($moduleInfo);

        $this->assertEquals($version, $this->versionChecker->getModuleVersion());
    }
}
