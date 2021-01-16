<?php

namespace Database\Factories;

use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $titleValidator = function($str) {
            return mb_strlen($str) > 5 && mb_strlen($str) < 100;
        };

        return [
                'slug' => $this->faker->unique()->regexify('/^[a-z0-9-_]+$/i'),
                'title' => $this->faker->valid($titleValidator)->sentence(5, true),
                'short_text' => $this->text(255),
                'body' => $this->faker->paragraph(5,true), // одно предложение
                'owner_id' => User::all()->random()->id // случайный id из пользователей
        ];
    }
}
