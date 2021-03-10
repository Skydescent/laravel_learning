<?php

namespace Database\Seeders;

use App\News;
use App\Post;
use App\Tag;
use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //TODO: ensure that tasks,posts,users tables not empty (first or create?)
        //TODO: rename seeder TagsToTaggableSeeder

        $tags = Tag::factory()
            ->times(50)
            ->create();

        $taggable = collect([
            User::class,
            Post::class,
            Task::class,
            News::class,
        ]);

        for($i = 1; $i <= rand(100,150); $i++){
            $model = $taggable->random(1)->first();
            $model::inRandomOrder()
                ->first()
                ->tags()
                ->save($tags->random(1)->first());
        }
    }
}
