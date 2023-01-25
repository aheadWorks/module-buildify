<?php
namespace Aheadworks\Buildify\Test\Unit\Model\Buildify\Entity;

use Aheadworks\Buildify\Api\Data\EntityFieldInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Buildify\Model\Buildify\Entity\LoadHandler;
use Aheadworks\Buildify\Model\Buildify\Entity\Repository;
use Aheadworks\Buildify\Api\Data\EntityFieldInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class LoadHandlerTest
 * @package Aheadworks\Buildify\Test\Unit\Model\Buildify\Entity
 */
class LoadHandlerTest extends TestCase
{
    /**
     * @var EntityFieldInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    /**
     * @var EntityFieldInterfaceFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $entityFieldFactoryMock;

    /**
     * @var EntityFieldInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $entityFieldMock;

    /**
     * @var LoadHandler
     */
    private $loadHandler;

    /**
     * Initialize model
     */
    public function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->repositoryMock = $this->createPartialMock(
            Repository::class,
            ['get']
        );
        $this->entityFieldFactoryMock = $this->createPartialMock(
            EntityFieldInterfaceFactory::class,
            ['create']
        );
        $this->entityFieldMock = $this->getMockForAbstractClass(EntityFieldInterface::class);

        $this->loadHandler = $objectManager->getObject(
            LoadHandler::class,
            [
                'repository' => $this->repositoryMock,
                'entityFieldFactory' => $this->entityFieldFactoryMock
            ]
        );
    }

    /**
     * Test load method
     */
    public function testLoad()
    {
        $entityId = 1;
        $entityType = 'test_type';

        $this->repositoryMock->expects($this->once())
            ->method('get')
            ->with($entityId, $entityType)
            ->willReturn($this->entityFieldMock);

        $this->assertEquals($this->entityFieldMock, $this->loadHandler->load($entityId, $entityType));
    }

    /**
     * Test load method
     */
    public function testLoadOnExeption()
    {
        $entityId = 1;
        $entityType = 'test_type';

        $this->repositoryMock->expects($this->once())
            ->method('get')
            ->with($entityId, $entityType)
            ->willThrowException(NoSuchEntityException::singleField('id', $entityId));
        $this->entityFieldFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->entityFieldMock);

        $this->assertEquals($this->entityFieldMock, $this->loadHandler->load($entityId, $entityType));
    }
}