<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Events;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EntityChangeTest extends TestCase
{
    /** @var EntityChange - System Under Test */
    protected $sut;

    /** @var IStringerEntity|MockObject */
    protected $entityMock;

    /** @var string */
    protected $eventType = 'foo';

    public function setUp(): void
    {
        $this->entityMock = $this->createMock(IStringerEntity::class);

        $this->sut = new EntityChange($this->entityMock, $this->eventType);

        parent::setUp();
    }

    public function testGetEntity()
    {
        $actualResult = $this->sut->getEntity();

        $this->assertSame($this->entityMock, $actualResult);
    }

    public function testGetEntityId()
    {
        $expectedResult = 'foo';

        $this->entityMock->expects($this->once())->method('getId')->willReturn($expectedResult);
        $actualResult = $this->sut->getEntityId();

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testGetEntityName()
    {
        $expectedResult = get_class($this->entityMock);

        $actualResult = $this->sut->getEntityName();

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testGetEntityType()
    {
        $actualResult = $this->sut->getEventType();

        $this->assertSame($this->eventType, $actualResult);
    }
}
