<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_task_of_authenticated_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);
        Auth::login($user);
        $response = $this->get('/task');
        dd($response);
        $response->assertStatus(200);
    }


    public function test_show_task_of_unauthenticated_user()
    {
        $response = $this->get('/task');
        $response->assertStatus(302);
    }

    // public function testShow()
    // {
    //     $task = Task::factory()->create();
    //
    //     $response = $this->get('/tasks/' . $task->id);
    //     $response->assertStatus(200);
    // }
    //
    // public function testStore()
    // {
    //     $task = Task::factory()->create();
    //     $response = $this->post('/tasks', $task->toArray());
    //     $response->assertStatus(201);
    // }
    //
    // public function testUpdate()
    // {
    //     $task = Task::factory()->create();
    //     $task->text = "Updated task text";
    //     $response = $this->put('/tasks/' . $task->id, $task->toArray());
    //     $response->assertStatus(200);
    // }
    //
    // public function testDestroy()
    // {
    //     $task = Task::factory()->create();
    //     $response = $this->delete('/tasks/' . $task->id);
    //     $response->assertStatus(200);
    // }
}
