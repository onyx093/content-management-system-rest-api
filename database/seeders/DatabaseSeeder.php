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

        $categories = Category::factory(5)->create();

        User::factory(2)->create()->each( function($user) use ($categories) {
            Post::factory(rand(2, 5))->create([
                'category_id' => $categories->random()->id,
                'user_id' => $user->id,
            ]);
        });
    }
}
