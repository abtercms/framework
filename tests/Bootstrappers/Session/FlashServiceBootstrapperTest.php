<?php

namespace AbterPhp\Framework\Bootstrappers\Session;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Environments\Environment;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Ioc\Container;
use Opulence\Sessions\ISession;
use PHPUnit\Framework\TestCase;

class FlashServiceBootstrapperTest extends TestCase
{
    /** @var FlashServiceBootstrapper */
    protected FlashServiceBootstrapper $sut;

    public function setUp(): void
    {
        $this->sut = new FlashServiceBootstrapper();
    }

    protected function tearDown(): void
    {
        Environment::unsetVar(Env::ENV_NAME);
    }

    public function testRegisterBindings()
    {
        Environment::setVar(Env::ENV_NAME, 'foo');

        $sessionMock    = $this->getMockBuilder(ISession::class)->getMock();
        $translatorMock = $this->getMockBuilder(ITranslator::class)->getMock();

        $container = new Container();
        $container->bindInstance(ISession::class, $sessionMock);
        $container->bindInstance(ITranslator::class, $translatorMock);

        $this->sut->registerBindings($container);

        $action = $container->resolve(FlashService::class);
        $this->assertInstanceOf(FlashService::class, $action);
    }
}
