<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAUserCanHaveTasks()
    {
        $user = User::factory()->create();

        $attributes = Task::factory()->raw(['owner_id' => $user]);

        $user->tasks()->create($attributes);

        //Удостоверимся, что заголовок из созданной фабрикой задачи
        // совпадает с заголовком первой задачи из базы данных по данному пользователю
        $this->assertEquals($attributes['title'], $user->tasks->first()->title);
    }
}
