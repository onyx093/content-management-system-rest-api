<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test to check if an authenticated user can get a collection of paginated posts.
     *
     * @return void
     */
    public function test_can_return_collection_of_paginated_posts()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'category_id', 'user_id', 'title', 'content', 'created_at', 'updated_at']
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /**
     * A basic feature test to check if an authenticated user can create a post.
     *
     * @return void
     */
    public function test_can_create_a_post()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $category = $this->createCategory();

        $response = $this->json('POST', '/api/v1/posts', [
            'category_id' => $category->id,
            'user_id' => $user->id,
            'title' => $title = $this->faker()->sentence(12),
            'content' => $content = $this->faker()->text(100),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'category_id', 'user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson([
                'category_id' => $category->id,
                'user_id' => $user->id,
                'title' => $title,
                'content' => $content,
            ]);
        $this->assertDatabaseHas('posts', [
            'category_id' => $category->id,
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
        ]);
    }

    /**
     * A basic feature test to check if an authenticated user will get 404 found if post to fetch doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_fetched_post_is_not_found()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/posts/-1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test to check if an authenticated user can get back a post.
     *
     * @return void
     */
    public function test_can_return_a_post()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $category = $this->createCategory();
        $post = $this->createPost([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $response = $this->json('GET', '/api/v1/posts/' . $post->id);
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'category_id', 'user_id', 'title', 'content', 'created_at', 'updated_at'])
            ->assertJson([
            'id' => $post->id,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'title' => $post->title,
            'content' => $post->content,
            'created_at' => (string) $post->created_at,
            'updated_at' => (string) $post->updated_at,
        ]);
    }

    /**
     * A basic feature test to check if an authenticated user will get 404 found if post to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_updated_post_is_not_found()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('PUT', '/api/v1/posts/-1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test to check if an authenticated user can update a post.
     *
     * @return void
     */
    public function test_can_update_a_post()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $category = $this->createCategory();
        $post = $this->createPost([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $updated_title = $post->title . "_updated";

        $response = $this->json('PUT', '/api/v1/posts/' . $post->id, [
            'category_id' => $post->category_id,
            'title' => $updated_title,
            'content' => $post->content,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'id' => $post->id,
                'category_id' => $category->id,
                'user_id' => $user->id,
                'title' => $updated_title,
                'content' => $post->content,
                'created_at' => (string) $post->created_at,
                'updated_at' => (string) $post->updated_at,
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => $updated_title,
        ]);
    }

    /**
     * A basic feature test to check if an authenticated user will get 404 found if post to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_post_to_delete_is_not_found()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('DELETE', '/api/v1/post/-1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test to check if an authenticated user can update a post.
     *
     * @return void
     */
    public function test_can_delete_a_post()
    {
        $user = $this->createUser();
        Passport::actingAs($user);
        $category = $this->createCategory();
        $post = $this->createPost([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $response = $this->json('DELETE', '/api/v1/posts/' . $post->id);
        $response->assertStatus(204)
            ->assertSee(null);

        $this->assertDatabaseMissing('posts', [
            'title' => $post->title,
            'content' => $post->content,
        ]);
    }
}
