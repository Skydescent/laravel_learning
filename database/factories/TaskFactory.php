<?php

namespace Database\Factories;

use App\Task;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3,true), // true - слова в виде строки
            'body' => $this->faker->sentence, // одно предложение
            'owner_id' => User::all()->random()->id, // случайный id из пользователей
            'type' => $this->faker->randomElement(['new', 'old', 'fast'])
        ];
    }
}
