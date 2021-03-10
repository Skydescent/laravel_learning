<?php

use Database\Seeders\NewsSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PostsSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\TagSeeder;
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
        $this->call([
            UserSeeder::class,
            PostsSeeder::class,
            CommentSeeder::class,
            NewsSeeder::class,
            TagSeeder::class,
        ]);
    }
}
