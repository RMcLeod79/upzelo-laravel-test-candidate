<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = new TaskCollection(Task::all());
        return response()->json($tasks);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        Task::create($data);

        return response()->json($data, 201);
    }

    public function show(int $id): JsonResponse
    {
        $task = Task::findOrFail($id)->load('project')->load('user');
        $resource = new TaskResource($task);
        return response()->json($resource);
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());
        return response()->json($task);
    }

    public function destroy(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json([], 204);
    }
}
