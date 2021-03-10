<?php

use Database\Seeders\NewsSeeder;
use Database\Seeders\TasksToUserSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PostsSeeder;
use Database\Seeders\CommentSeeder;
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
            TasksToUserSeeder::class,
            UserSeeder::class,
            PostsSeeder::class,
            CommentSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
