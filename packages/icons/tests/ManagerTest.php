<?php

namespace Reedware\Icons\Tests;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Mockery;
use Mockery\MockInterface;
use Reedware\Icons\Manager;
use Tests\Unit\TestCase;

class ManagerTest extends TestCase
{
    /** @test */
    public function icon_without_cache()
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $cache = $manager->getCache();

        $cache
            ->shouldReceive('rememberForever')
            ->withArgs(
                fn ($a0) => $a0 == 'icons.foo',
                fn ($a1) => $a1 instanceof Closure
            )
            ->once()
            ->andReturnUsing(function ($a0, $a1) {
                return $a1();
            });


        /** @var MockInterface */
        $view = $manager->getViewFactory();

        $view
            ->shouldReceive('make')
            ->with('icons.foo')
            ->once()
            ->andReturn(Mockery::mock(View::class, function (MockInterface $mock) {
                $mock
                    ->shouldReceive('render')
                    ->once()
                    ->andReturn('foo.svg');
            }));

        $actual = $manager->icon('foo');
        $expected = 'foo.svg';

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function icon_with_cache()
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $cache = $manager->getCache();

        $cache
            ->shouldReceive('rememberForever')
            ->withArgs(
                fn ($a0) => $a0 == 'icons.foo',
                fn ($a1) => $a1 instanceof Closure
            )
            ->once()
            ->andReturn('foo.svg');


        /** @var MockInterface */
        $view = $manager->getViewFactory();

        $view->shouldNotReceive('make');

        $actual = $manager->icon('foo');
        $expected = 'foo.svg';

        $this->assertEquals($expected, $actual);
    }

    /**
     * Creates a new manager for testing.
     */
    protected function newManager(): Manager
    {
        return new Manager(
            Mockery::mock(Repository::class),
            Mockery::mock(Factory::class)
        );
    }
}
