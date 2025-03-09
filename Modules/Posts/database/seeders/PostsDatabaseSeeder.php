<?php

namespace Modules\Posts\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Posts\Database\Seeders\PostSeeder;

class PostsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PostSeeder::class,
        ]);
    }
}
