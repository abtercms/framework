<?php

namespace AbterPhp\Framework\Bootstrappers\Assets;

use AbterPhp\Framework\Assets\CacheManager\ICacheManager;
use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Environments\Environment;
use Opulence\Ioc\Container;
use PHPUnit\Framework\TestCase;

class CacheManagerBootstrapperTest extends TestCase
{
    /** @var CacheManagerBootstrapper */
    protected CacheManagerBootstrapper $sut;

    public function setUp(): void
    {
        $this->sut = new CacheManagerBootstrapper();
    }

    protected function tearDown(): void
    {
        Environment::unsetVar(Env::ENV_NAME);
        Environment::unsetVar(Env::DIR_MEDIA);
        Environment::unsetVar(Env::CACHE_BASE_PATH);
    }

    public function testRegisterBindingsDevelopment()
    {
        Environment::setVar(Env::ENV_NAME, Environment::DEVELOPMENT);
        Environment::setVar(Env::DIR_MEDIA, '/tmp/foo');
        Environment::setVar(Env::CACHE_BASE_PATH, '/tmp/bar');

        $container = new Container();

        $this->sut->registerBindings($container);

        $actual = $container->resolve(ICacheManager::class);
        $this->assertInstanceOf(ICacheManager::class, $actual);
    }

    public function testRegisterBindingsProduction()
    {
        Environment::setVar(Env::ENV_NAME, Environment::PRODUCTION);
        Environment::setVar(Env::DIR_MEDIA, '/tmp/foo');
        Environment::setVar(Env::CACHE_BASE_PATH, '/tmp/bar');

        $container = new Container();

        $this->sut->registerBindings($container);

        $actual = $container->resolve(ICacheManager::class);
        $this->assertInstanceOf(ICacheManager::class, $actual);
    }
}
