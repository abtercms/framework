<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Module;

use AbterPhp\Framework\Constant\Module;
use Opulence\Cache\ICacheBridge;
use PHPUnit\Framework\MockObject\MockObject;
use AbterPhp\Framework\Constant\Event;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @var Manager */
    protected $sut;

    /** @var Loader|MockObject */
    protected $loaderMock;

    /** @var ICacheBridge|null|MockObject */
    protected $cacheMock;

    public function setUp()
    {
        $this->cacheMock = null;

        $this->loaderMock = $this->getMockBuilder(Loader::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadModules'])
            ->getMock();

        $this->sut = new Manager($this->loaderMock);

        parent::setUp();
    }

    public function createSutWithCache()
    {
        $this->cacheMock = $this->getMockBuilder(Loader::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadModules'])
            ->getMock();

        $this->sut = new Manager($this->loaderMock, $this->cacheMock);

        parent::setUp();
    }

    /**
     * @return array
     */
    public function getHttpBootstrapperProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::BOOTSTRAPPERS      => [
                            'Assets\Bootstrappers\AssetManagerBootstrapper',
                            'Template\Bootstrappers\TemplateEngineBootstrapper',
                        ],
                        Module::CLI_BOOTSTRAPPERS  => [
                            'Databases\Bootstrappers\MigrationsBootstrapper',
                        ],
                        Module::HTTP_BOOTSTRAPPERS => [
                            'Navigation\Bootstrappers\PrimaryBootstrapper',
                            'Views\Bootstrappers\ViewFunctionsBootstrapper',
                        ],
                    ],
                ],
                [
                    'Assets\Bootstrappers\AssetManagerBootstrapper',
                    'Template\Bootstrappers\TemplateEngineBootstrapper',
                    'Navigation\Bootstrappers\PrimaryBootstrapper',
                    'Views\Bootstrappers\ViewFunctionsBootstrapper',
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::BOOTSTRAPPERS      => [
                            'Assets\Bootstrappers\AssetManagerBootstrapper',
                            'Template\Bootstrappers\TemplateEngineBootstrapper',
                        ],
                        Module::CLI_BOOTSTRAPPERS  => [
                            'Databases\Bootstrappers\MigrationsBootstrapper',
                        ],
                        Module::HTTP_BOOTSTRAPPERS => [
                            'Navigation\Bootstrappers\PrimaryBootstrapper',
                            'Views\Bootstrappers\ViewFunctionsBootstrapper',
                        ],
                    ],
                    [
                        Module::BOOTSTRAPPERS      => [
                            'Bootstrappers\Orm\OrmBootstrapper',
                            'Bootstrappers\Validation\ValidatorBootstrapper',
                        ],
                        Module::CLI_BOOTSTRAPPERS  => [
                            'Bootstrappers\Console\Commands\CommandsBootstrapper',
                        ],
                        Module::HTTP_BOOTSTRAPPERS => [
                            'Bootstrappers\Http\Controllers\Execute\LoginBootstrapper',
                            'Bootstrappers\Vendor\SlugifyBootstrapper',
                        ],
                    ]
                ],
                [
                    'Assets\Bootstrappers\AssetManagerBootstrapper',
                    'Template\Bootstrappers\TemplateEngineBootstrapper',
                    'Navigation\Bootstrappers\PrimaryBootstrapper',
                    'Views\Bootstrappers\ViewFunctionsBootstrapper',
                    'Bootstrappers\Orm\OrmBootstrapper',
                    'Bootstrappers\Validation\ValidatorBootstrapper',
                    'Bootstrappers\Http\Controllers\Execute\LoginBootstrapper',
                    'Bootstrappers\Vendor\SlugifyBootstrapper',
                ],
            ],

        ];
    }

    /**
     * @dataProvider getHttpBootstrapperProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetHttpBootstrappers(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getHttpBootstrappers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getCliBootstrapperProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::BOOTSTRAPPERS      => [
                            'Assets\Bootstrappers\AssetManagerBootstrapper',
                            'Template\Bootstrappers\TemplateEngineBootstrapper',
                        ],
                        Module::CLI_BOOTSTRAPPERS  => [
                            'Databases\Bootstrappers\MigrationsBootstrapper',
                        ],
                        Module::HTTP_BOOTSTRAPPERS => [
                            'Navigation\Bootstrappers\PrimaryBootstrapper',
                            'Views\Bootstrappers\ViewFunctionsBootstrapper',
                        ],
                    ],
                ],
                [
                    'Assets\Bootstrappers\AssetManagerBootstrapper',
                    'Template\Bootstrappers\TemplateEngineBootstrapper',
                    'Databases\Bootstrappers\MigrationsBootstrapper',
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::BOOTSTRAPPERS      => [
                            'Assets\Bootstrappers\AssetManagerBootstrapper',
                            'Template\Bootstrappers\TemplateEngineBootstrapper',
                        ],
                        Module::CLI_BOOTSTRAPPERS  => [
                            'Databases\Bootstrappers\MigrationsBootstrapper',
                        ],
                        Module::HTTP_BOOTSTRAPPERS => [
                            'Navigation\Bootstrappers\PrimaryBootstrapper',
                            'Views\Bootstrappers\ViewFunctionsBootstrapper',
                        ],
                    ],
                    [
                        Module::BOOTSTRAPPERS      => [
                            'Bootstrappers\Orm\OrmBootstrapper',
                            'Bootstrappers\Validation\ValidatorBootstrapper',
                        ],
                        Module::CLI_BOOTSTRAPPERS  => [
                            'Bootstrappers\Console\Commands\CommandsBootstrapper',
                        ],
                        Module::HTTP_BOOTSTRAPPERS => [
                            'Bootstrappers\Http\Controllers\Execute\LoginBootstrapper',
                            'Bootstrappers\Vendor\SlugifyBootstrapper',
                        ],
                    ]
                ],
                [
                    'Assets\Bootstrappers\AssetManagerBootstrapper',
                    'Template\Bootstrappers\TemplateEngineBootstrapper',
                    'Databases\Bootstrappers\MigrationsBootstrapper',
                    'Bootstrappers\Orm\OrmBootstrapper',
                    'Bootstrappers\Validation\ValidatorBootstrapper',
                    'Bootstrappers\Console\Commands\CommandsBootstrapper',
                ],
            ],

        ];
    }

    /**
     * @dataProvider getCliBootstrapperProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetCliBootstrappers(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getCliBootstrappers();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getCommandsProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::COMMANDS           => [
                            'Console\Commands\User\Create',
                            'Console\Commands\User\Delete',
                        ],
                    ],
                ],
                [
                    'Console\Commands\User\Create',
                    'Console\Commands\User\Delete',
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::COMMANDS           => [
                            'Console\Commands\User\Create',
                            'Console\Commands\User\Delete',
                        ],
                    ],
                    [
                        Module::COMMANDS           => [
                            'Assets\Command\FlushCache',
                            'Authorization\Command\FlushCache',
                        ],
                    ]
                ],
                [
                    'Console\Commands\User\Create',
                    'Console\Commands\User\Delete',
                    'Assets\Command\FlushCache',
                    'Authorization\Command\FlushCache',
                ],
            ],

        ];
    }

    /**
     * @dataProvider getCommandsProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetCommands(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getCommands();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getEventsProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::EVENTS             => [
                            Event::TEMPLATE_ENGINE_READY => [
                                'Events\Listeners\TemplateRegistrar@register',
                            ],
                            Event::NAVIGATION_READY      => [
                                'Events\Listeners\NavigationRegistrar@register',
                            ],
                            Event::ENTITY_POST_CHANGE    => [
                                'Events\Listeners\PageInvalidator@register',
                            ],
                            Event::DASHBOARD_READY       => [
                                'Events\Listeners\DashboardRegistrar@register',
                            ],
                        ],
                    ],
                ],
                [
                    Event::TEMPLATE_ENGINE_READY => [
                        'Events\Listeners\TemplateRegistrar@register',
                    ],
                    Event::NAVIGATION_READY      => [
                        'Events\Listeners\NavigationRegistrar@register',
                    ],
                    Event::ENTITY_POST_CHANGE    => [
                        'Events\Listeners\PageInvalidator@register',
                    ],
                    Event::DASHBOARD_READY       => [
                        'Events\Listeners\DashboardRegistrar@register',
                    ],
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::EVENTS             => [
                            Event::TEMPLATE_ENGINE_READY => [
                                'Module1\Events\Listeners\TemplateRegistrar@register',
                            ],
                            Event::NAVIGATION_READY      => [
                                'Module1\Events\Listeners\NavigationRegistrar@register',
                            ],
                            Event::ENTITY_POST_CHANGE    => [
                                'Module1\Events\Listeners\PageInvalidator@register',
                            ],
                            Event::DASHBOARD_READY       => [
                                'Module1\Events\Listeners\DashboardRegistrar@register',
                            ],
                        ],
                    ],
                    [
                        Module::EVENTS             => [
                            Event::AUTH_READY         => [
                                'Module2\Events\Listeners\AuthRegistrar@register',
                            ],
                            Event::NAVIGATION_READY   => [
                                'Module2\Events\Listeners\NavigationRegistrar@register',
                            ],
                            Event::ENTITY_POST_CHANGE => [
                                'Module2\Events\Listeners\AuthInvalidator@register',
                            ],
                            Event::DASHBOARD_READY    => [
                                'Module2\Events\Listeners\DashboardRegistrar@register',
                            ],
                        ],
                    ]
                ],
                [
                    Event::TEMPLATE_ENGINE_READY => [
                        'Module1\Events\Listeners\TemplateRegistrar@register',
                    ],
                    Event::NAVIGATION_READY      => [
                        'Module1\Events\Listeners\NavigationRegistrar@register',
                        'Module2\Events\Listeners\NavigationRegistrar@register',
                    ],
                    Event::ENTITY_POST_CHANGE    => [
                        'Module1\Events\Listeners\PageInvalidator@register',
                        'Module2\Events\Listeners\AuthInvalidator@register',
                    ],
                    Event::DASHBOARD_READY       => [
                        'Module1\Events\Listeners\DashboardRegistrar@register',
                        'Module2\Events\Listeners\DashboardRegistrar@register',
                    ],
                    Event::AUTH_READY         => [
                        'Module2\Events\Listeners\AuthRegistrar@register',
                    ],
                ],
            ],

        ];
    }

    /**
     * @dataProvider getEventsProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetEvents(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getEvents();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getMiddlewareProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::MIDDLEWARE         => [
                            1000 => [
                                'Http\Middleware\CheckCsrfToken',
                                'Http\Middleware\Security',
                            ],
                        ],
                    ],
                ],
                [
                    'Http\Middleware\CheckCsrfToken',
                    'Http\Middleware\Security',
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::MIDDLEWARE         => [
                            1000 => [
                                'Module1\Http\Middleware\CheckCsrfToken',
                                'Module1\Http\Middleware\Security',
                            ],
                        ],
                    ],
                    [
                        Module::MIDDLEWARE             => [
                            500 => [
                                'Module2\Http\Middleware\Session',
                            ],
                            1000 => [
                                'Module2\Http\Middleware\EnvironmentWarning',
                                'Module2\Http\Middleware\Security',
                            ],
                        ],
                    ]
                ],
                [
                    'Module2\Http\Middleware\Session',
                    'Module1\Http\Middleware\CheckCsrfToken',
                    'Module1\Http\Middleware\Security',
                    'Module2\Http\Middleware\EnvironmentWarning',
                    'Module2\Http\Middleware\Security',
                ],
            ],

        ];
    }

    /**
     * @dataProvider getMiddlewareProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetMiddleware(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getMiddleware();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getRoutePathsProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::ROUTE_PATHS        => [
                            600 => [
                                'Module1/routes-early.php',
                                'Module1/routes-early2.php',
                            ],
                            50000 => [
                                'Module1/routes-late.php',
                            ],
                        ],
                    ],
                ],
                [
                    'Module1/routes-late.php',
                    'Module1/routes-early.php',
                    'Module1/routes-early2.php',
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::ROUTE_PATHS        => [
                            600 => [
                                'Module1/routes-early.php',
                                'Module1/routes-early2.php',
                            ],
                            50000 => [
                                'Module1/routes-late.php',
                            ],
                        ],
                    ],
                    [
                        Module::ROUTE_PATHS        => [
                            -20 => [
                                'Module2/routes-really-early.php',
                            ],
                            700 => [
                                'Module2/routes-early.php',
                            ],
                            50000 => [
                                'Module2/routes-late.php',
                            ],
                        ],
                    ]
                ],
                [
                    'Module1/routes-late.php',
                    'Module2/routes-late.php',
                    'Module2/routes-early.php',
                    'Module1/routes-early.php',
                    'Module1/routes-early2.php',
                    'Module2/routes-really-early.php',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getRoutePathsProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetRoutePaths(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getRoutePaths();

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     */
    public function getMigrationPathsProvider(): array
    {
        return [
            'no-modules' => [[], []],
            'one-module' => [
                [
                    [
                        Module::MIGRATION_PATHS        => [
                            600 => [
                                'Module1/migrations-early.php',
                                'Module1/migrations-early2.php',
                            ],
                            50000 => [
                                'Module1/migrations-late.php',
                            ],
                        ],
                    ],
                ],
                [
                    'Module1/migrations-early.php',
                    'Module1/migrations-early2.php',
                    'Module1/migrations-late.php',
                ],
            ],
            'two-modules' => [
                [
                    [
                        Module::MIGRATION_PATHS        => [
                            600 => [
                                'Module1/migrations-early.php',
                                'Module1/migrations-early2.php',
                            ],
                            50000 => [
                                'Module1/migrations-late.php',
                            ],
                        ],
                    ],
                    [
                        Module::MIGRATION_PATHS        => [
                            -20 => [
                                'Module2/migrations-really-early.php',
                            ],
                            700 => [
                                'Module2/migrations-early.php',
                            ],
                            50000 => [
                                'Module2/migrations-late.php',
                            ],
                        ],
                    ]
                ],
                [
                    'Module2/migrations-really-early.php',
                    'Module1/migrations-early.php',
                    'Module1/migrations-early2.php',
                    'Module2/migrations-early.php',
                    'Module1/migrations-late.php',
                    'Module2/migrations-late.php',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getMigrationPathsProvider
     *
     * @param array $modules
     * @param array $expectedResult
     */
    public function testGetMigrationPaths(array $modules, array $expectedResult)
    {
        $this->loaderMock->expects($this->any())->method('loadModules')->willReturn($modules);

        $actualResult = $this->sut->getMigrationPaths();

        $this->assertEquals($expectedResult, $actualResult);
    }
}