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
        $tasks = $request->user()->tasks();

        $priorityOrder = $request->input('priority_order', 'asc');
        $dateOrder = $request->input('date_order', 'asc');

        if ($request->has('statuses')) {
            $tasks->filterStatus($request->statuses);
        }

        if ($request->has('search')) {
            $tasks->searchText($request->search);
        }

        if ($request->has('priority')) {
            $priorityOrder = $request->input('priority') == 'asc' ? 'asc' : 'desc';
        }

        if ($request->has('date')) {
            $dateOrder = $request->input('date') == 'asc' ? 'asc' : 'desc';
        }

        $filteredTasks =  $tasks->orderBy('priority', $priorityOrder)
                                ->orderBy('created_at', $dateOrder)
                                ->get();

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
        $data['attachment'] = '';
        $data['status'] = 'inProgress';
        $data['priority'] = '1';

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
