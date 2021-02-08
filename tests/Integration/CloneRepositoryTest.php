<?php

namespace Tests\Integration;

use App\Jobs\CloneRepository;
use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CloneRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->preventRunningGitClone();
    }

    /**
     * @test
     */
    public function unreachable_host_prevents_cloning_and_sets_repository_status_id_to_invalid()
    {
        /** @var Repository $repository */
        $repository = Repository::factory()->create(['url' => 'https://github123.com/AttilaSzendi/biotech.git']);

        $class = new CloneRepository($repository);

        $class->handle();

        $this->assertEquals(Repository::INVALID, $repository->fresh()->status_id);
    }

    /**
     * @test
     */
    public function in_case_of_already_cloned_repository_sets_repository_status_id_to_invalid()
    {
        $url = 'https://github.com/AttilaSzendi/biotech.git';
        exec('rm -rf ~/data/biotech');
        exec("git clone $url ~/data/biotech");

        /** @var Repository $repository */
        $repository = Repository::factory()->create([
            'url' => $url,
            'name' => 'biotech'
        ]);

        $class = new CloneRepository($repository);

        $class->handle();

        $this->assertEquals(Repository::INVALID, $repository->fresh()->status_id);

        exec('rm -rf ~/data/biotech');
    }

    /**
     * @test
     */
    public function repository_can_be_cloned_and_sets_repository_status_id_to_cloned()
    {
        exec('rm -rf ~/data/biotech');

        /** @var Repository $repository */
        $repository = Repository::factory()->create([
            'url' => 'https://github.com/AttilaSzendi/biotech.git',
            'name' => 'biotech'
        ]);

        $class = new CloneRepository($repository);

        $class->handle();

        $this->assertEquals(Repository::CLONED, $repository->fresh()->status_id);

        exec('rm -rf ~/data/biotech');
    }

    protected function preventRunningGitClone(): void
    {
        Event::fake();
    }
}
