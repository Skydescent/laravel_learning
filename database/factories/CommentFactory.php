<?php

namespace Database\Factories;

use App\Comment;
use App\User;
use App\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $commentable = [
            \App\Post::class,
            \App\News::class,
        ];
        $class = $this->faker->randomElement($commentable);
        $alias = (new $class())->getMorphClass();

        $commentable = $class::inRandomOrder()->first() ?? $class::factory()->create() ;

        return [
            'body' => $this->faker->sentence(6, true),
            'author_id' => User::all()
                ->filter(function($item) {
                    return !$item->isAdmin();
                })->random()
                ->id,
            'commentable_type' => $alias,
            'commentable_id' => $commentable->id,
        ];
    }
}
