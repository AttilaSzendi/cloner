<?php

namespace Tests\Feature;

use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RepositoryIndexTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE_NAME = 'repositories.index';

    protected function setUp(): void
    {
        parent::setUp();

        $this->preventRunningGitClone();
    }

    /**
     * @test
     */
    public function repositories_can_be_listed()
    {
        $count = 3;

        Repository::factory()->count($count)->create();

        $response = $this->getJson(route(static::ROUTE_NAME));

        $response->assertJsonCount($count, 'data');

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'url',
                    'name',
                    'lastCommitMessage',
                    'statusId',
                    'createdAt',
                    'updatedAt',
                ]
            ]
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function repository_list_can_be_controlled_by_pagination()
    {
        $count = 16;

        Repository::factory()->count($count)->create();

        $response = $this->getJson(route(static::ROUTE_NAME, ['page' => 2]));

        $response->assertJsonCount(1, 'data');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function repository_list_size_can_be_set_via_get_parameter()
    {
        $count = 5;

        Repository::factory()->count($count)->create();

        $response = $this->getJson(route(static::ROUTE_NAME, ['size' => 4]));

        $response->assertJsonCount(4, 'data');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function repository_list_can_be_filtered_by_name()
    {
        Repository::factory()->create(['name' => 'anything']);
        Repository::factory()->create();

        $response = $this->getJson(route(static::ROUTE_NAME, ['name' => 'yth']));

        $response->assertJsonCount(1, 'data');

        $response->assertStatus(Response::HTTP_OK);
    }

    protected function preventRunningGitClone(): void
    {
        Event::fake();
    }
}
