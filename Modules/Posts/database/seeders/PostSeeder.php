<?php

namespace Modules\Posts\Database\Seeders;

use Modules\Posts\Models\Post;
use Illuminate\Database\Seeder;
use Modules\Posts\Database\Factories\PostFactory;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PostFactory::new()->count(10)->create();
        // create 3 posts for
        PostFactory::new()
            ->count(3)
            ->forUser([
                'name' => 'abdelkader',
            ])
            ->create();


    }
}
