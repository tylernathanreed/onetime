<?php

namespace Reedware\Secrets\Tests;

use Illuminate\Contracts\Encryption\Encrypter;
use Mockery;
use Mockery\MockInterface;
use Reedware\Secrets\CompressionEncrypter;
use Reedware\Secrets\Contracts\SecretCompressor;

class CompressionEncrypterTest extends TestCase
{
    /** @test */
    public function it_encrypts_compressed_values()
    {
        $encrypter = $this->newEncrypter();

        /** @var MockInterface */
        $compressor = $encrypter->getCompressor();

        $compressor
            ->shouldReceive('compress')
            ->with('raw')
            ->andReturn('compressed');

        /** @var MockInterface */
        $base = $encrypter->getEncrypter();

        $base
            ->shouldReceive('encrypt')
            ->with('compressed')
            ->andReturn('encrypted');

        $this->assertEquals('encrypted', $encrypter->encrypt('raw'));
    }

    /** @test */
    public function it_decrypts_uncompressed_values()
    {
        $encrypter = $this->newEncrypter();

        /** @var MockInterface */
        $base = $encrypter->getEncrypter();

        $base
            ->shouldReceive('decrypt')
            ->with('encrypted')
            ->andReturn('compressed');

        /** @var MockInterface */
        $compressor = $encrypter->getCompressor();

        $compressor
            ->shouldReceive('decompress')
            ->with('compressed')
            ->andReturn('raw');

        $this->assertEquals('raw', $encrypter->decrypt('encrypted'));
    }

    /** @test */
    public function it_returns_the_base_encrypter()
    {
        $encrypter = $this->newEncrypter();

        $base = $encrypter->getEncrypter();

        $this->assertInstanceOf(Encrypter::class, $base);
    }

    /** @test */
    public function it_returns_the_compressor()
    {
        $encrypter = $this->newEncrypter();

        $compressor = $encrypter->getCompressor();

        $this->assertInstanceOf(SecretCompressor::class, $compressor);
    }

    /**
     * Creates and returns a new compression encrypter for testing.
     */
    protected function newEncrypter(): CompressionEncrypter
    {
        return new CompressionEncrypter(
            Mockery::mock(Encrypter::class),
            Mockery::mock(SecretCompressor::class)
        );
    }
}
