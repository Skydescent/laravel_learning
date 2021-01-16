<?php

namespace Database\Seeders;

use App\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()
            ->times(5)
            ->create();
    }
}
