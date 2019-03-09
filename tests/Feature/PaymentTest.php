<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    // TODO:
    // - It shows the payment page
    // - The payment page can be submitted
    // Test different sources: https://stripe.com/docs/testing
    // It shows whats wrong when something happens
    // It requires valid inputs
    // It shows a message and redirects on success
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_payment_page()
    {
        $response = $this->get('/payment');

        $response->assertSee('Payment');
        $response->assertOk();
    }
}