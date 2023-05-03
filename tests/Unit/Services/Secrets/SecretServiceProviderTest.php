<?php

namespace Tests\Unit\Services\Secrets;

use App\Services\Secrets\CacheRepository as SecretCacheRepository;
use App\Services\Secrets\CompressionEncrypter;
use App\Services\Secrets\Contracts\SecretCompressor;
use App\Services\Secrets\Contracts\SecretEncrypter;
use App\Services\Secrets\Contracts\SecretKeyGenerator;
use App\Services\Secrets\Contracts\SecretManager;
use App\Services\Secrets\Contracts\SecretRepository;
use App\Services\Secrets\GzCompressor;
use App\Services\Secrets\Manager;
use App\Services\Secrets\RandomSlugGenerator;
use App\Services\Secrets\SecretServiceProvider;
use Closure;
use Illuminate\Cache\ArrayStore;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Routing\Router;
use Laravel\Octane\Cache\OctaneStore;
use Mockery;
use Mockery\MockInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Unit\TestCase;

class SecretServiceProviderTest extends TestCase
{
    /** @test */
    public function it_binds_the_key_generator_to_the_container()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);

        $this->assertTrue($this->container->bound(SecretKeyGenerator::class));

        $generator = $this->container->make(SecretKeyGenerator::class);

        $this->assertInstanceOf(RandomSlugGenerator::class, $generator);
    }

    /** @test */
    public function it_binds_the_repository_to_the_container()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);

        $this->assertTrue($this->container->bound(SecretRepository::class));

        $this->mockAs('cache', CacheFactory::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('store')
                ->with('secrets')
                ->once()
                ->andReturn(Mockery::mock(CacheRepository::class, function (MockInterface $mock) {
                    $mock
                        ->shouldReceive('getStore')
                        ->once()
                        ->andReturn(Mockery::mock(ArrayStore::class));
                }));
        });

        $repository = $this->container->make(SecretRepository::class);

        $this->assertInstanceOf(SecretCacheRepository::class, $repository);
    }

    /** @test */
    public function it_respects_the_octane_cache_limits_for_the_repository()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);

        $this->assertTrue($this->container->bound(SecretRepository::class));

        $this->mockAs('cache', CacheFactory::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('store')
                ->with('secrets')
                ->once()
                ->andReturn(Mockery::mock(CacheRepository::class, function (MockInterface $mock) {
                    $mock
                        ->shouldReceive('getStore')
                        ->once()
                        ->andReturn(Mockery::mock(OctaneStore::class));
                }));
        });

        $this->mockAs('config', ConfigRepository::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('get')
                ->with('octane.cache.rows')
                ->andReturn(100);

            $mock
                ->shouldReceive('get')
                ->with('octane.cache.bytes')
                ->andReturn(200);
        });

        /** @var SecretRepository */
        $repository = $this->container->make(SecretRepository::class);

        $this->assertInstanceOf(SecretCacheRepository::class, $repository);

        $this->assertEquals(100, $repository->getMaxCount());
        $this->assertEquals(200, $repository->getMaxLength());
    }

    /** @test */
    public function it_binds_the_encrypter_to_the_container()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);

        $this->assertTrue($this->container->bound(SecretEncrypter::class));

        $this->mockAs('encrypter', Encrypter::class);
        $this->mock(SecretCompressor::class);

        $encrypter = $this->container->make(SecretEncrypter::class);

        $this->assertInstanceOf(CompressionEncrypter::class, $encrypter);
    }

    /** @test */
    public function it_binds_the_compressor_to_the_container()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);

        $this->assertTrue($this->container->bound(SecretCompressor::class));

        $encrypter = $this->container->make(SecretCompressor::class);

        $this->assertInstanceOf(GzCompressor::class, $encrypter);
    }

    /** @test */
    public function it_binds_the_manager_to_the_container()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);

        $this->assertTrue($this->container->bound(SecretManager::class));
        $this->assertTrue($this->container->bound('secrets'));

        $this->mockManagerDependencies();

        $manager = $this->container->make(SecretManager::class);

        $this->assertEquals($manager, $this->container->make('secrets'));
        $this->assertInstanceOf(Manager::class, $manager);
    }

    /** @test */
    public function it_binds_secrets_to_the_router()
    {
        $this->registerServiceProvider(SecretServiceProvider::class);
        $this->mockManagerDependencies();

        $this->container->make(SecretRepository::class)
            ->shouldReceive('has')
            ->with('foo')
            ->once()
            ->andReturn(true);

        $this->container->singleton('router', function () {
            return Mockery::mock(Router::class, function (MockInterface $mock) {
                $mock->callback = null;

                $mock
                    ->shouldReceive('bind')
                    ->withArgs(
                        fn ($a0) => $a0 == 'secret',
                        fn ($a1) => $a1 instanceof Closure
                    )
                    ->andReturnUsing(function ($a0, $a1) use ($mock) {
                        $mock->callback = $a1;
                    });
            });
        });

        $router = $this->container->make('router');

        $this->assertEquals('foo', ($router->callback)('foo'));
    }


    /** @test */
    public function it_throws_when_route_bound_secrets_are_not_found()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Your secret does not exist, or has expired.');

        $this->registerServiceProvider(SecretServiceProvider::class);
        $this->mockManagerDependencies();

        $this->container->make(SecretRepository::class)
            ->shouldReceive('has')
            ->with('foo')
            ->once()
            ->andReturn(false);

        $this->container->singleton('router', function () {
            return Mockery::mock(Router::class, function (MockInterface $mock) {
                $mock->callback = null;

                $mock
                    ->shouldReceive('bind')
                    ->withArgs(
                        fn ($a0) => $a0 == 'secret',
                        fn ($a1) => $a1 instanceof Closure
                    )
                    ->andReturnUsing(function ($a0, $a1) use ($mock) {
                        $mock->callback = $a1;
                    });
            });
        });

        $router = $this->container->make('router');

        ($router->callback)('foo');
    }

    /** @test */
    public function it_provides_deferred_services()
    {
        $provider = $this->newServiceProvider(SecretServiceProvider::class);

        $this->assertInstanceOf(DeferrableProvider::class, $provider);

        $this->assertEquals([
            SecretCompressor::class,
            SecretEncrypter::class,
            SecretManager::class
        ], $provider->provides());
    }

    /**
     * Mocks the dependencies for the secrets manager.
     */
    protected function mockManagerDependencies(): void
    {
        $this->mock(SecretKeyGenerator::class);
        $this->mock(SecretRepository::class);
        $this->mock(SecretEncrypter::class);
    }
}
