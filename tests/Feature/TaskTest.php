<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    // RefreshDatabase - необходимо чтобы данный трейт использовался
    // чтобы тестовые данные, которые будут заполнятся в БД, потом убирались
    // Терйт WithFaker - для использование автоматически генерируемых данных
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAUserCanCreateATask()
        //Метод должен называться с test
    {
        // Что - входные данные: Авторизованный пользователь
        $this->actingAs(User::factory()->create());

        // Когда: Приходит на страницу /tasks для создания новой задачи
        $this->post('/tasks', [
           'title' => $this->faker->words(4, true),
           'body' => $this->faker->sentence,
        ]);

        // Что нужно получить на выходе
    }
}
