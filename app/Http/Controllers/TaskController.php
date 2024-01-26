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
        $tasks = $request->user()->tasks()->orderBy('id', 'desc')->paginate(5);
        return TaskResource::collection($tasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function store(TaskRequest $request)
    {
        $task = $request->user()->tasks()->create([
            'text' => $request->text,
            'img' => '',
            'thumb' => '',
            'status' => 'inProgress',
        ]);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request)
    {
        $task = Task::findOrFail($request->id);

        $task->update([
            'text' => $request->text,
            'status' => $request->status,
        ]);

        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $this->deleteImageFiles($task);

        $task->delete();

        return response()->json('task deleted');
    }

    private function deleteImageFiles(Task $task)
    {
        if ($task->img !== '') {
            Storage::delete([
                'public/' . $task->img,
                'public/' . $task->thumb
            ]);
        }
    }
}
