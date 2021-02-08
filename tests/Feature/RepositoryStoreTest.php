<?php

namespace Tests\Feature;

use App\Jobs\CloneRepository;
use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RepositoryStoreTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE_NAME = 'repositories.store';

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();
    }

    /**
     * @test
     */
    public function a_repository_can_be_stored()
    {
        $createData = ['url' => 'https://github.com/AttilaSzendi/biotech.git'];

        $response = $this->postJson(route(static::ROUTE_NAME), $createData);

        $this->assertDatabaseHas('repositories', [
            'id' => $response->json('data.id'),
            'url' => $createData['url'],
            'name' => 'biotech',
            'last_commit_message' => null,
            'status_id' => Repository::INITIALIZED
        ]);

        Queue::assertPushed(CloneRepository::class);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
