<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test to check if an authenticated user can create a post.
     *
     * @return void
     */
    public function test_can_create_a_post()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/posts', []);

        $response->assertStatus(201);
    }
}
