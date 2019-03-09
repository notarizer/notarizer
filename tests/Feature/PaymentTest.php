<?php

namespace Tests\Feature;

use App\Payments\Payments;
use Stripe\Error\Api;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    // TODO: Test the \App\Payments\StripePayments class

    /** @test */
    public function it_shows_the_payment_page()
    {
        $response = $this->get('/payment');

        $response->assertSee('Payment');
        $response->assertOk();
    }

    /** @test */
    public function it_can_be_submitted()
    {
        $this->withoutExceptionHandling();

        $this->mock(Payments::class, function($mock) {
            $mock->shouldReceive()
                ->charge(20000, 'tok_visa', 'test@email.com', null)
                ->once();
        });

        $response = $this->post('/payment', [
            'amount' => '200', // $200
            'stripeToken' => 'tok_visa', // See: https://stripe.com/docs/testing
            'email' => 'test@email.com'
        ]);

        $response->assertRedirect('/');

        $response->assertSessionHas('payment_confirmation');
    }

    /** @test */
    public function it_requires_valid_input()
    {
        $this->mock(Payments::class, function($mock) {
            $mock->shouldNotReceive()
                ->charge(20000, 'tok_visa', 'test@email.com', null);
        });

        $response = $this->post('/payment', [
            'amount' => 'abc', // $200
            'stripeToken' => null, // See: https://stripe.com/docs/testing
            'email' => 'not_an_email'
        ]);

        $response->assertSessionHasErrors(['amount', 'stripeToken', 'email']);
    }
    
    /** @test */
    public function it_redirects_to_for_document()
    {
        $this->mockPayments();

        $response = $this->post('/payment', [
            'amount' => '200', // $200
            'stripeToken' => 'tok_visa', // See: https://stripe.com/docs/testing
            'email' => 'test@email.com',
            'for' => '2xdozNp7fnVbNP4HAmlpv8QRoE06cKLY6vJOKHuTbjIXT1P5go9k0GtWn7MW0oxM'
        ]);

        $response->assertRedirect('/doc/2xdozNp7fnVbNP4HAmlpv8QRoE06cKLY6vJOKHuTbjIXT1P5go9k0GtWn7MW0oxM');

        $response->assertSessionHas('payment_confirmation');
    }
    
    /** @test */
    public function it_shows_when_there_is_a_problem()
    {
        $this->mock(Payments::class, function($mock) {
            $mock->shouldReceive('charge')
                ->andThrow(Api::class);
        });

        $response = $this->post('/payment', [
            'amount' => '200', // $200
            'stripeToken' => 'tok_visa', // See: https://stripe.com/docs/testing
            'email' => 'test@email.com',
        ]);

        $response->assertSessionHas('payment_error');


        $response2 = $this->followingRedirects()->post('/payment', [
            'amount' => '200', // $200
            'stripeToken' => 'tok_visa', // See: https://stripe.com/docs/testing
            'email' => 'test@email.com',
        ]);

        $response2->assertSeeText('Your payment of $200.00 failed!');
    }


    private function mockPayments()
    {
        $this->mock(Payments::class, function($mock) {
            $mock->shouldReceive('charge');
        });
    }
}