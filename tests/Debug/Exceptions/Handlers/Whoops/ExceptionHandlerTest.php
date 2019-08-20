<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Debug\Exceptions\Handlers\Whoops;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

class ExceptionHandlerTest extends TestCase
{
    /** @var ExceptionHandler - System Under Test */
    protected $sut;

    /** @var LoggerInterface|MockObject */
    protected $loggerMock;

    /** @var ExceptionRenderer|MockObject */
    protected $exceptionRendererMock;

    public function setUp(): void
    {
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->exceptionRendererMock = $this->createMock(ExceptionRenderer::class);

        $this->sut = new ExceptionHandler($this->loggerMock, $this->exceptionRendererMock, []);

        parent::setUp();
    }

    public function testHandleRendersException()
    {
        $exceptionStub = new \Exception();

        $this->exceptionRendererMock->expects($this->atLeastOnce())->method('render')->with($exceptionStub);

        $this->sut->handle($exceptionStub);
    }

    public function testRegisterPushesTextHandlerByDefault()
    {
        $whoopsRunMock = $this->createWhoopsRunMock();

        $this->exceptionRendererMock->expects($this->any())->method('getRun')->willReturn($whoopsRunMock);

        $whoopsRunMock
            ->expects($this->at(0))
            ->method('pushHandler')
            ->with(new \Whoops\Handler\PlainTextHandler($this->loggerMock));

        $whoopsRunMock->expects($this->at(1))->method('register');

        $this->sut->register();
    }

    public function testRegisterPushesPrettyHandlerIfSapiIsHttp()
    {
        $whoopsRunMock = $this->createWhoopsRunMock();

        $this->exceptionRendererMock->expects($this->any())->method('getRun')->willReturn($whoopsRunMock);

        $this->sut->setSapi('http');

        $whoopsRunMock
            ->expects($this->at(0))
            ->method('pushHandler')
            ->with(new PlainTextHandler($this->loggerMock));

        $whoopsRunMock
            ->expects($this->at(1))
            ->method('pushHandler')
            ->with(new PrettyPageHandler());

        $whoopsRunMock->expects($this->at(2))->method('register');

        $this->sut->register();
    }

    /**
     * @return MockObject|RunInterface
     */
    protected function createWhoopsRunMock()
    {
        return $this->createMock(RunInterface::class);
    }
}
