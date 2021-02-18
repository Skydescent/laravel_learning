<?php

namespace Database\Seeders;

use App\Post;
use App\User;
use App\Tag;
use Illuminate\Database\Seeder;

class PostsToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()
            ->times(2)
            ->create();
        $tags = Tag::factory()
            ->times(10)
            ->create();

        Post::factory()
            ->times(20)
            ->for()
            ->hasAttached($tags->random(3))
            ->create(['owner_id' => $users->random(1)->id()]); // в атрибутах первый пользователь
    }
}
