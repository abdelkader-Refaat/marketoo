<?php
namespace Modules\Posts\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Posts\App\Models\Post;

class PostsDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory()->count(10)->create();
    }
}
