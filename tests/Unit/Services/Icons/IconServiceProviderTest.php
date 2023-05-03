<?php

namespace Tests\Unit\Services\Icons;

use App\Services\Icons\Contracts\Manager as ManagerContract;
use App\Services\Icons\IconServiceProvider;
use App\Services\Icons\Manager;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\View\Compilers\BladeCompiler;
use Mockery;
use Mockery\MockInterface;
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
                    fn ($a1) => $a1 instanceof Closure && $a1('"foo"') == "<?php echo app('icons')->icon(\"foo\"); ?>"
                )
                ->once();
        }));

        $this->bootServiceProvider(IconServiceProvider::class);
    }
}
