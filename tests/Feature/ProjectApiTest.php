<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_project(): void
    {
        $user = User::factory()->create();
        
        $projectData = [
            'name' => 'Test Project',
            'description' => 'A test project description',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/v1/projects', $projectData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'description',
                        'user_id',
                        'created_at',
                        'updated_at',
                    ]
                ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'user_id' => $user->id,
        ]);
    }

    public function test_can_list_projects(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'tasks_count',
                        ]
                    ]
                ]);
    }

    // Add more test stubs for candidates to implement (OPTIONAL):
    // test_can_show_project_with_tasks()
    // test_project_validation_rules()
}
