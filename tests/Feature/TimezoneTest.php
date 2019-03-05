<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimezoneTest extends TestCase
{

    /** @test */
    public function it_sets_the_timezone()
    {
        $response = $this->post('/timezone', ['timezone' => 'Asia/Singapore']);

        $response->assertSessionHas('timezone', 'Asia/Singapore');
        $response->assertRedirect();
    }

    /** @test */
    public function it_doesnt_set_invalid_timezones()
    {
        $response = $this->post('/timezone', ['timezone' => 'Not a timezone']);

        $response->assertSessionMissing('timezone');
        $response->assertRedirect();
    }

    /** @test */
    public function it_shows_the_time_in_the_doc()
    {
        $this->post('/timezone', ['timezone' => 'Asia/Singapore']);

        $doc = factory(\App\Document::class)->create();

        $response = $this->get($doc->path());

        $time = $doc->created_at->setTimezone('Asia/Singapore')->format('F j, Y, g:i a T');

        $response->assertSee($time);
    }
}