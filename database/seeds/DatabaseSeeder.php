<?php

use Database\Seeders\TasksToUserSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PostsSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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
        ]);
    }
}
