<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomepageTest extends TestCase
{

    /** @test */
    public function it_loads_the_homepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Instantly Timestamp Any File');
    }
}
