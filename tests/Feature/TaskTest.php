<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task->text,
                    'image' => $task->img,
                    'thumb' => $task->thumb,
                ],
            ],
        ]);
    }


    public function test_show_task_of_unauthenticated_user()
    {
        $response = $this->get('/task');
        $response->assertStatus(302);
    }


    public function testStore()
    {
        $user = User::factory()->create();
        Auth::login($user);
        $task = [
            'text' => 'exampletext',
        ];
        $response = $this->post('/task', $task);
        // dd($response);

        $response->assertJson([
            'data' => [
                'text' => 'exampletext',
                'status' => 'inProgress',
                'image' => '',
                'thumb' => '',
            ],
        ]);

        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);
        Auth::login($user);

        $task->text = "Updated task text";
        $task->status = "done";
        $response = $this->put('/task/' . $task->id, $task->toArray());

        $response->assertJson([
            'data' => [
                'text' => $task->text,
                'status' => $task->status,
            ],
        ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);
        Auth::login($user);

        $response = $this->delete('/task/' . $task->id);
        $response->assertStatus(200);
    }
}
