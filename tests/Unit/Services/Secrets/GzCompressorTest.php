<?php

namespace Tests\Unit\Services\Secrets;

use App\Services\Secrets\GzCompressor;
use Tests\Unit\TestCase;

class GzCompressorTest extends TestCase
{
    /** @test */
    public function it_compresses_using_gzcompress()
    {
        $compressor = $this->newCompressor();

        $expected = gzcompress('foo');
        $actual = $compressor->compress('foo');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_decompresses_using_gzuncompress()
    {
        $compressor = $this->newCompressor();

        $expected = 'foo';
        $actual = $compressor->decompress(gzcompress('foo'));

        $this->assertEquals($expected, $actual);
    }

    /**
     * Creates and returns a new compressor for testing.
     */
    protected function newCompressor(): GzCompressor
    {
        return new GzCompressor;
    }
}
