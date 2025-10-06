<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->has('status')) {
            $tasks = Task::where('status', $request->status)->get();
        } else {
            $tasks = Task::all();
        }
        $tasks = new TaskCollection($tasks);
        return response()->json($tasks);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        $task = Task::create($data);

        return new TaskResource($task)->response()->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $task = Task::findOrFail($id)->load('project')->load('user');

        return new TaskResource($task)->response()->setStatusCode(200);
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
