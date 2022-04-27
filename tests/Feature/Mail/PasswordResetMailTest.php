<?php

namespace Tests\Feature\Mail;

use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetMailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test for laravel passport authentication to logout a user.
     *
     * @return void
     */
    public function test_can_send_password_reset_mail()
    {
        Mail::fake();
        $password_reset = PasswordReset::factory()->create();
        Mail::send(new PasswordResetMail(['token' => $password_reset->token]));
        Mail::assertSent(PasswordResetMail::class);
    }

}
