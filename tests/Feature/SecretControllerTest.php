<?php

namespace Tests\Feature;

class SecretControllerTest extends TestCase
{
    /** @test */
    public function it_loads_the_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
