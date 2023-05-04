<?php

namespace Reedware\Icons\Tests;

use Closure;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\View\Compilers\BladeCompiler;
use Mockery;
use Mockery\MockInterface;
use Reedware\Icons\Contracts\Manager as ManagerContract;
use Reedware\Icons\IconServiceProvider;
use Reedware\Icons\Manager;
use Tests\Unit\TestCase;

class IconServiceProviderTest extends TestCase
{
    /** @test */
    public function register()
    {
        $this->registerServiceProvider(IconServiceProvider::class);

        $this->assertTrue($this->container->bound(ManagerContract::class));
        $this->assertTrue($this->container->bound(Manager::class));
        $this->assertTrue($this->container->bound('icons'));

        $this->container->instance('cache', Mockery::mock(CacheFactory::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('store')
                ->with('icons')
                ->andReturn(Mockery::mock(Repository::class));
        }));

        $this->container->instance('view', Mockery::mock(ViewFactory::class));

        $manager = $this->container->make(ManagerContract::class);

        $this->assertEquals($manager, $this->container->make(Manager::class));
        $this->assertEquals($manager, $this->container->make('icons'));
    }

    /** @test */
    public function boot()
    {
        $this->container->instance('blade.compiler', Mockery::mock(BladeCompiler::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('directive')
                ->withArgs(
                    fn ($a0) => $a0 == 'icon',
                    fn ($a1) => $a1 instanceof Closure
                )
                ->once()
                ->andReturnUsing(function ($a0, $a1) {
                    $this->assertEquals(
                        "<?php echo app('icons')->icon(\"foo\"); ?>",
                        $a1('"foo"')
                    );
                });
        }));

        $this->bootServiceProvider(IconServiceProvider::class);
    }

    /** @test */
    public function test_provides()
    {
        $provider = $this->newServiceProvider(IconServiceProvider::class);

        $this->assertEquals([
            ManagerContract::class
        ], $provider->provides());
    }
}
