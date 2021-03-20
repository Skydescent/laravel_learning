<?php

use Database\Seeders\NewsSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PostsSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\TagToTaggablesSeeder;
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
            NewsSeeder::class,
            TagToTaggablesSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
