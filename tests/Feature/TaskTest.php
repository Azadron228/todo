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

        $task1 = Task::factory()->create([
            'user_id' => $user->id,
            'text' => 'Task with status in_progress',
            'status' => 'in_progress',
        ]);

        $task2 = Task::factory()->create([
            'user_id' => $user->id,
            'text' => 'Task with status completed',
            'status' => 'completed',
        ]);

        Auth::login($user);

        $response = $this->get('/task');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task1->text,
                    'attachment' => $task1->attachment,
                ],
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                ],
            ],
        ]);

        // Test with status filter
        $response = $this->get('/task?statuses[]=in_progress');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task1->text,
                    'attachment' => $task1->attachment,
                    'status' => $task1->status,
                ],
            ],
        ]);

        // Test with search filter
        $response = $this->get('/task?search=completed');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                    'status' => 'completed',
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
                'attachment' => '',
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
