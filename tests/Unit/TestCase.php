<?php

namespace Tests\Unit;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Mockery;
use Mockery\Exception\InvalidCountException;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase as Base;

abstract class TestCase extends Base
{
    /**
     * The container instance.
     */
    protected ContainerContract $container;

    /**
     * Prepares the environment before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpContainer();
    }

    /**
     * Prepares the container before each test.
     */
    protected function setUpContainer(): void
    {
        $this->container ??= Container::getInstance();
        $this->container->instance(ContainerContract::class, $this->container);

        Facade::setFacadeApplication($this->container);
    }

    /**
     * Creates the specified service provider.
     */
    protected function newServiceProvider(string $provider): ServiceProvider
    {
        return new $provider($this->container);
    }

    /**
     * Registers the specified service provider.
     */
    protected function registerServiceProvider(string $provider): void
    {
        $this->newServiceProvider($provider)->register();
    }

    /**
     * Boots the specified service provider.
     */
    protected function bootServiceProvider(string $provider): void
    {
        $this->newServiceProvider($provider)->boot();
    }

    /**
     * Binds the specified instance to the container.
     */
    protected function instance(string $abstract, object $instance): object
    {
        $this->container->instance($abstract, $instance);

        return $instance;
    }

    /**
     * Mocks the specified instance within the container.
     */
    protected function mock(string $abstract, Closure $mock = null): MockInterface
    {
        return $this->mockAs($abstract, $abstract, $mock);
    }

    /**
     * Mocks the specified instance under the given alias within the container.
     */
    protected function mockAs(string $alias, string $abstract, Closure $mock = null): MockInterface
    {
        return $this->instance($alias, Mockery::mock(...array_filter([$abstract, $mock])));
    }

    /**
     * Partially mocks the specified instance within the container.
     */
    protected function partialMock(string $abstract, Closure $mock = null): MockInterface
    {
        return $this->partialMockAs($abstract, $abstract, $mock);
    }

    /**
     * Partially mocks the specified instance within the container.
     */
    protected function partialMockAs(string $alias, string $abstract, Closure $mock = null): MockInterface
    {
        return $this->instance($alias, Mockery::mock(...array_filter([$abstract, $mock]))->makePartial());
    }

    /**
     * Tears down the environment after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownMockery();
        $this->tearDownContainer();
    }

    /**
     * Tears down mockery after each test.
     */
    protected function tearDownMockery(): void
    {
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        try {
            Mockery::close();
        } catch (InvalidCountException $e) {
            if (! Str::contains($e->getMethodName(), ['doWrite', 'askQuestion'])) {
                throw $e;
            }
        }
    }

    /**
     * Tears down the container after each test.
     */
    protected function tearDownContainer(): void
    {
        Facade::setFacadeApplication(null);
        Facade::clearResolvedInstances();

        $this->container->flush();
    }
}
