<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Session;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\I18n\MockTranslatorFactory;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;

class FlashServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var FlashService */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    public function setUp()
    {
        parent::setUp();

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'flash'])
            ->getMock();

        $this->translatorMock = MockTranslatorFactory::createSimpleTranslator($this, []);

        $this->sut = new FlashService($this->sessionMock, $this->translatorMock);
    }

    /**
     * @return array
     */
    public function mergeSuccessMessagesProvider(): array
    {
        return [
            [
                ['AAA' => 'BBB'],
                ['BBB' => 'CCC'],
                ['AAA' => 'BBB', 'BBB' => 'CCC'],
            ],
        ];
    }

    /**
     * @dataProvider mergeSuccessMessagesProvider
     *
     * @param array $oldMessages
     * @param array $newMessages
     * @param array $expectedResult
     */
    public function testMergeSuccessMessages(array $oldMessages, array $newMessages, array $expectedResult)
    {
        $this->sessionMock
            ->expects($this->any())
            ->method('get')
            ->willReturn($oldMessages);

        $this->sessionMock
            ->expects($this->atLeastOnce())
            ->method('flash')
            ->with(FlashService::SUCCESS, $expectedResult);

        $this->sut->mergeSuccessMessages($newMessages);
    }

    /**
     * @return array
     */
    public function mergeErrorMessagesProvider(): array
    {
        return [
            [
                ['AAA', 'BBB'],
                ['BBB' => ['CCC']],
                ['AAA', 'BBB', 'CCC'],
            ],
        ];
    }

    /**
     * @dataProvider mergeErrorMessagesProvider
     *
     * @param array $oldMessages
     * @param array $newMessages
     * @param array $expectedResult
     */
    public function testMergeErrorMessages(array $oldMessages, array $newMessages, array $expectedResult)
    {
        $this->sessionMock
            ->expects($this->any())
            ->method('get')
            ->willReturn($oldMessages);

        $this->sessionMock
            ->expects($this->atLeastOnce())
            ->method('flash')
            ->with(FlashService::ERROR, $expectedResult);

        $this->sut->mergeErrorMessages($newMessages);
    }

    public function testRetrieveSuccessMessages()
    {
        $expectedResult = ['bar'];

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(FlashService::SUCCESS)
            ->willReturn($expectedResult);

        $actualResult = $this->sut->retrieveSuccessMessages();

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testRetrieveErrorMessages()
    {
        $expectedResult = ['bar'];

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->with(FlashService::ERROR)
            ->willReturn($expectedResult);

        $actualResult = $this->sut->retrieveErrorMessages();

        $this->assertSame($expectedResult, $actualResult);
    }
}