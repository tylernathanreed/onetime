<?php

namespace Reedware\Secrets\Tests;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Contracts\Cache\Repository;
use Mockery;
use Mockery\MockInterface;
use Reedware\Secrets\CacheRepository;

class CacheRepositoryTest extends TestCase
{
    /** @test */
    public function it_defers_to_the_cache_for_existence()
    {
        $repository = $this->newRepository();

        /** @var MockInterface */
        $cache = $repository->getCache();

        $cache
            ->shouldReceive('has')
            ->with('foo')
            ->andReturn(true, false);

        $this->assertTrue($repository->has('foo'));
        $this->assertFalse($repository->has('foo'));
    }

    /** @test */
    public function it_defers_to_the_cache_for_retrieval()
    {
        $repository = $this->newRepository();

        /** @var MockInterface */
        $cache = $repository->getCache();

        $cache
            ->shouldReceive('pull')
            ->with('foo')
            ->once()
            ->andReturn('bar');

        $this->assertEquals('bar', $repository->pull('foo'));
    }

    /**
     * @test
     * @dataProvider ttlDataProvider
     */
    public function it_defers_to_the_cache_for_persistence(DateTimeInterface|string|null $ttl)
    {
        Carbon::setTestNow('2023-05-01');

        $expected = is_string($ttl) ? Carbon::parse($ttl) : $ttl;

        $repository = $this->newRepository();

        /** @var MockInterface */
        $cache = $repository->getCache();

        $cache
            ->shouldReceive('put')
            ->withArgs(
                fn ($a0) => $a0 === 'key',
                fn ($a1) => $a1 === 'foo',
                fn ($a2) => $a2 == $expected
            )
            ->once();

        if (is_null($ttl)) {
            $repository->put('key', 'foo');
        } else {
            $repository->put('key', 'foo', $ttl);
        }
    }

    public static function ttlDataProvider()
    {
        return [
            'ttl none' => [null],
            'ttl string' => ['+1 day'],
            'ttl datetime' => [Carbon::parse('+1 day')]
        ];
    }

    /** @test */
    public function it_defers_to_the_cache_for_removal()
    {
        $repository = $this->newRepository();

        /** @var MockInterface */
        $cache = $repository->getCache();

        $cache
            ->shouldReceive('forget')
            ->with('key')
            ->once()
            ->andReturn(true);

        $this->assertTrue($repository->forget('key'));
    }

    /** @test */
    public function it_returns_the_max_count()
    {
        $repository = $this->newRepository();

        $this->assertEquals(100, $repository->getMaxCount());
    }

    /** @test */
    public function it_returns_the_max_length()
    {
        $repository = $this->newRepository();

        $this->assertEquals(200, $repository->getMaxLength());
    }

    /** @test */
    public function it_returns_the_cache()
    {
        $repository = $this->newRepository();

        $cache = $repository->getCache();

        $this->assertInstanceOf(Repository::class, $cache);
    }

    /**
     * Creates and returns a new secret repository for testing.
     */
    protected function newRepository(): CacheRepository
    {
        return new CacheRepository(
            Mockery::mock(Repository::class),
            100,
            200
        );
    }
}
