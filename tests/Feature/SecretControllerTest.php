<?php

namespace Tests\Feature;

use App\Services\Secrets\Contracts\SecretManager;
use App\Services\Secrets\Events\SecretCreated;
use App\Services\Secrets\Events\SecretDestroyed;
use App\Services\Secrets\Events\SecretRetrieved;
use Illuminate\Support\Facades\Event;

class SecretControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_home_page()
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_creates_secrets()
    {
        $key = null;

        Event::listen(SecretCreated::class, function (SecretCreated $event) use (&$key) {
            $key = $event->key;
        });

        $response = $this->post(route('store'), [
            'secret' => 'foo'
        ]);

        $response->assertStatus(200);

        $this->assertNotNull($key);

        $response->assertSee(route('show', ['secret' => $key]));
    }

    /** @test */
    public function it_validates_secret_length_before_creating()
    {
        $this->app->make('config')->set('cache.stores.secrets.limits', [
            'count' => 100,
            'length' => 100
        ]);

        $secret = str_repeat('foo', 1000);

        $response = $this->post(route('store'), [
            'secret' => $secret
        ]);

        $response->assertSessionHasErrors([
            'secret' => 'The secret must not be greater than 100 characters.'
        ]);
    }

    /** @test */
    public function it_validates_secrets_before_showing()
    {
        $response = $this->get(route('show', [
            'secret' => 'not-yet-created'
        ]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_shows_secrets_without_revealing()
    {
        /** @var SecretManager */
        $secrets = $this->app->make(SecretManager::class);

        $key = $secrets->store('foo');

        $response = $this->get(route('show', [
            'secret' => $key
        ]));

        $response
            ->assertStatus(200)
            ->assertSee(route('reveal', ['secret' => $key]));
    }

    /** @test */
    public function it_validates_secrets_before_revealing()
    {
        $response = $this->get(route('reveal', [
            'secret' => 'not-yet-created'
        ]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_reveals_and_destroys_secrets()
    {
        /** @var SecretManager */
        $secrets = $this->app->make(SecretManager::class);

        $key = $secrets->store('foo');
        $retrieved = false;

        Event::listen(SecretRetrieved::class, function (SecretRetrieved $event) use ($key, &$retrieved) {
            $this->assertEquals($key, $event->key);

            $retrieved = true;
        });

        $response = $this->get(route('reveal', [
            'secret' => $key
        ]));

        $response
            ->assertStatus(200)
            ->assertSee('foo');

        $this->assertTrue($retrieved);

        $this->assertFalse($secrets->has($key));
    }

    /** @test */
    public function it_validates_secrets_before_destroying()
    {
        $response = $this->get(route('destroy', [
            'secret' => 'not-yet-created'
        ]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_destroys_secrets()
    {
        /** @var SecretManager */
        $secrets = $this->app->make(SecretManager::class);

        $key = $secrets->store('foo');
        $destroyed = false;

        Event::listen(SecretDestroyed::class, function (SecretDestroyed $event) use ($key, &$destroyed) {
            $this->assertEquals($key, $event->key);

            $destroyed = true;
        });

        $response = $this->get(route('destroy', [
            'secret' => $key
        ]));

        $response
            ->assertStatus(200)
            ->assertDontSee('foo')
            ->assertDontSee($key);

        $this->assertTrue($destroyed);

        $this->assertFalse($secrets->has($key));
    }
}
