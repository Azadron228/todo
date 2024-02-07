<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filteredTasks = Task::filterAndOrder($request)->paginate();

        return TaskResource::collection($filteredTasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $task = $request->user()->tasks()->create($data);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $this->deleteImageFiles($task);
        $task->delete();

        return response()->json('task deleted');
    }

    private function deleteImageFiles(Task $task)
    {
        if ($task->attachment !== '') {
            Storage::delete([
                'public/' . $task->attachment,
            ]);
        }
    }
}
