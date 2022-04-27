<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    /**
     * A basic feature test for laravel passport authentication to send forgot password link.
     *
     * @return void
     */
    public function test_can_reset_password()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
