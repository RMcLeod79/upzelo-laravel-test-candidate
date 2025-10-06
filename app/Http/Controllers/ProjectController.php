<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with('tasks')->get();
        $collection = new ProjectCollection($projects);
        return response()->json($collection);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $project = Project::create($data);

        return new ProjectResource($project)->response()->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $project = Project::with('tasks')->findOrFail($id);
        $resource = new ProjectResource($project);
        return response()->json($resource);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->update($request->all());
        return response()->json($project);
    }

    public function destroy(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json([], 204);
    }
}
