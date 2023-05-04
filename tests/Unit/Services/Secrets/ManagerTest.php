<?php

namespace Tests\Unit\Services\Secrets;

use App\Services\Secrets\Contracts\SecretEncrypter;
use App\Services\Secrets\Contracts\SecretKeyGenerator;
use App\Services\Secrets\Contracts\SecretRepository;
use App\Services\Secrets\Events\SecretCreated;
use App\Services\Secrets\Events\SecretDestroyed;
use App\Services\Secrets\Events\SecretRetrieved;
use App\Services\Secrets\Manager;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use Mockery\MockInterface;
use Tests\Unit\TestCase;

class ManagerTest extends TestCase
{
    /** @test */
    public function it_defers_to_the_repository_for_existence()
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $repository = $manager->getRepository();

        $repository
            ->shouldReceive('has')
            ->with('foo')
            ->andReturn(true, false);

        $this->assertTrue($manager->has('foo'));
        $this->assertFalse($manager->has('foo'));
    }

    /** @test */
    public function it_returns_null_when_the_repository_misses()
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $repository = $manager->getRepository();

        $repository
            ->shouldReceive('pull')
            ->with('foo')
            ->once()
            ->andReturn(null);

        $this->assertEquals(null, $manager->get('foo'));
    }

    /** @test */
    public function it_decrypts_when_the_repository_hits()
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $repository = $manager->getRepository();

        $repository
            ->shouldReceive('pull')
            ->with('foo')
            ->once()
            ->andReturn('my-encrypted-and-compressed-string');

        /** @var MockInterface */
        $encrypter = $manager->getEncrypter();

        $encrypter
            ->shouldReceive('decrypt')
            ->with('my-encrypted-and-compressed-string')
            ->once()
            ->andReturn('bar');

        /** @var MockInterface */
        $events = $manager->getDispatcher();

        $events
            ->shouldReceive('dispatch')
            ->withArgs(function (SecretRetrieved $event) {
                return $event->key === 'foo';
            })
            ->once();

        $this->assertEquals('bar', $manager->get('foo'));
    }

    /**
     * @test
     * @dataProvider ttlDataProvider
     */
    public function it_stores_encrypted_values(DateTimeInterface|string|null $ttl)
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $generator = $manager->getKeyGenerator();

        $generator
            ->shouldReceive('generate')
            ->with('foo')
            ->once()
            ->andReturn('key');

        /** @var MockInterface */
        $encrypter = $manager->getEncrypter();

        $encrypter
            ->shouldReceive('encrypt')
            ->with('foo')
            ->once()
            ->andReturn('my-encrypted-and-compressed-string');

        /** @var MockInterface */
        $repository = $manager->getRepository();

        $repository
            ->shouldReceive('put')
            ->with('key', 'my-encrypted-and-compressed-string', $ttl)
            ->once();

        /** @var MockInterface */
        $events = $manager->getDispatcher();

        $events
            ->shouldReceive('dispatch')
            ->withArgs(function (SecretCreated $event) {
                return $event->key === 'key';
            })
            ->once();

        if (is_null($ttl)) {
            $manager->store('foo');
        } else {
            $manager->store('foo', $ttl);
        }
    }

    public static function ttlDataProvider()
    {
        return [
            'ttl none' => [ null ],
            'ttl string' => [ '+1 day' ],
            'ttl datetime' => [ Carbon::parse('+1 day') ]
        ];
    }

    /** @test */
    public function it_deletes_from_the_repository()
    {
        $manager = $this->newManager();

        /** @var MockInterface */
        $repository = $manager->getRepository();

        $repository
            ->shouldReceive('forget')
            ->with('key')
            ->once()
            ->andReturn(true);

        /** @var MockInterface */
        $events = $manager->getDispatcher();

        $events
            ->shouldReceive('dispatch')
            ->withArgs(function (SecretDestroyed $event) {
                return $event->key === 'key';
            })
            ->once();

        $this->assertTrue($manager->delete('key'));
    }

    /** @test */
    public function it_returns_the_key_generator()
    {
        $manager = $this->newManager();

        $generator = $manager->getKeyGenerator();

        $this->assertInstanceOf(SecretKeyGenerator::class, $generator);
    }

    /** @test */
    public function it_returns_the_repository()
    {
        $manager = $this->newManager();

        $repository = $manager->getRepository();

        $this->assertInstanceOf(SecretRepository::class, $repository);
    }

    /** @test */
    public function it_returns_the_encrypter()
    {
        $manager = $this->newManager();

        $encrypter = $manager->getEncrypter();

        $this->assertInstanceOf(SecretEncrypter::class, $encrypter);
    }

    /**
     * Creates and returns a new secret manager for testing.
     */
    protected function newManager(): Manager
    {
        return new Manager(
            Mockery::mock(SecretKeyGenerator::class),
            Mockery::mock(SecretRepository::class),
            Mockery::mock(SecretEncrypter::class),
            Mockery::mock(Dispatcher::class)
        );
    }
}
