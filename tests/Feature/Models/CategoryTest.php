<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test to check if an authenticated user can get a collection of paginated categories.
     *
     * @return void
     */
    public function test_can_return_collection_of_paginated_category()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at']
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'path', 'per_page', 'total']
            ]);
    }

    /**
     * A basic feature test to check if an authenticated user can create a category.
     *
     * @return void
     */
    public function test_can_create_a_category()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('POST', '/api/v1/categories', [
            'name' => $name = $this->faker()->word()
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson([
                'name' => $name,
            ]);
        $this->assertDatabaseHas('categories', [
            'name' => $name,
        ]);
    }

    /**
     * A basic feature test to check if an authenticated user will get 404 found if category to fetch doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_fetched_category_is_not_found()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/categories/-1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test to check if an authenticated user can get back a category.
     *
     * @return void
     */
    public function test_can_return_a_category()
    {
        $category = $this->createCategory();
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('GET', '/api/v1/categories/' . $category->id);
        $response->assertStatus(200)
            ->assertJson([
            'id' => $category->id,
            'name' => $category->name,
            'created_at' => (string) $category->created_at,
            'updated_at' => (string) $category->updated_at,
        ]);
    }

    /**
     * A basic feature test to check if an authenticated user will get 404 found if category to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_updated_category_is_not_found()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('PUT', '/api/v1/categories/-1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test to check if an authenticated user can update a category.
     *
     * @return void
     */
    public function test_can_update_a_category()
    {
        $category = $this->createCategory();
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('PUT', '/api/v1/categories/' . $category->id, [
            'name' => $category->name . "_updated",
        ]);
        $response->assertStatus(200)
            ->assertJson([
            'id' => $category->id,
            'name' => $category->name . "_updated",
            'created_at' => (string) $category->created_at,
            'updated_at' => (string) $category->updated_at,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => $category->name . "_updated",
        ]);
    }

    /**
     * A basic feature test to check if an authenticated user will get 404 found if category to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_category_to_delete_is_not_found()
    {
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('DELETE', '/api/v1/categories/-1');
        $response->assertStatus(404);
    }

    /**
     * A basic feature test to check if an authenticated user can update a category.
     *
     * @return void
     */
    public function test_can_delete_a_category()
    {
        $category = $this->createCategory();
        $user = $this->createUser();
        Passport::actingAs($user);

        $response = $this->json('DELETE', '/api/v1/categories/' . $category->id);
        $response->assertStatus(204)
            ->assertSee(null);

        $this->assertDatabaseMissing('categories', [
            'name' => $category->name,
        ]);
    }
}
