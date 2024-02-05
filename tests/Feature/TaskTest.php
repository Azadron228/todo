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

        sleep(1);

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
                    'id'=>$task1->id,
                    'text' => $task1->text,
                    'status'=> $task1->status,
                    'attachment' => $task1->attachment,
                ],
                [
                    'id'=>$task2->id,
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                ],
            ],
        ]);

        // Test with search filter
        $response = $this->get("/task?search=completed");
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                    'status' => $task2->status,
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

        // Test with priority sorting (explicit ascending)
        $response = $this->get('/task?priority=asc');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                    'priority' => $task2->priority,
                ],
                [
                    'text' => $task1->text,
                    'attachment' => $task1->attachment,
                    'priority' => $task1->priority,
                ],
            ],
        ]);

        // Test with priority sorting (descending)
        $response = $this->get('/task?priority_order=desc');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task1->text,
                    'attachment' => $task1->attachment,
                    'priority' => $task1->priority,
                ],
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                    'priority' => $task2->priority,
                ],
            ],
        ]);

        // Test with date sorting (explicit ascending)
        $response = $this->get('/task?date=asc');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                    'priority' => $task2->priority,
                ],
                [
                    'text' => $task1->text,
                    'attachment' => $task1->attachment,
                    'priority' => $task1->priority,
                ],
            ],
        ]);

        // Test with date sorting (descending)
        $response = $this->get('/task?date=desc');
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    'text' => $task1->text,
                    'attachment' => $task1->attachment,
                    'priority' => $task1->priority,
                ],
                [
                    'text' => $task2->text,
                    'attachment' => $task2->attachment,
                    'priority' => $task2->priority,
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
            'priority' => '12'
        ];
        $response = $this->post('/task', $task);

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
