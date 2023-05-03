<?php

namespace Tests\Unit\Services\Secrets;

use App\Services\Secrets\RandomSlugGenerator;
use Tests\Unit\TestCase;

class RandomSlugGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_using_random_slugs()
    {
        $generator = $this->newGenerator();
        $keys = [];

        for ($i = 0; $i < 100; $i++) {
            $keys[$i] = $generator->generate('foo');

            $this->assertMatchesRegularExpression("/[a-z0-9]{32}/", $keys[$i]);
        }

        $this->assertEquals($keys, array_unique($keys));
    }

    /**
     * Creates and returns a new generator for testing.
     */
    protected function newGenerator(): RandomSlugGenerator
    {
        return new RandomSlugGenerator;
    }
}
