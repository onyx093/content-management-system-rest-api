<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     UserSeeder::class,
        // ]);

        $users = User::factory(2)->create();

        Category::factory(20)->create()->each( function($category) use ($users) {
            Post::factory(rand(2, 5))->create([
                'category_id' => $category->id,
                'user_id' => $users->random()->id,
            ]);
        });
    }
}
