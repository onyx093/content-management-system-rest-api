<?php

namespace Tests;

use App\Models\User;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createCategory(array $attributes = []): CategoryResource
    {
        $category = Category::factory()->create($attributes);
        return new CategoryResource($category);
    }

    public function createUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        return $user;
    }

    public function createPost(array $attributes = []): PostResource
    {
        $post = Post::factory()->create($attributes);
        return new PostResource($post);
    }
}
