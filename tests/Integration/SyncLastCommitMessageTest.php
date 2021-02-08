<?php

namespace Tests\Integration;

use App\Jobs\SyncLastCommitMessage;
use App\Models\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SyncLastCommitMessageTest extends TestCase
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
    public function in_case_of_already_cloned_repository_sets_repository_status_id_to_invalid()
    {
        $url = 'https://github.com/AttilaSzendi/biotech.git';
        exec('rm -rf ~/data/biotech');
        exec("git clone $url ~/data/biotech");

        /** @var Repository $repository */
        $repository = Repository::factory()->cloned()->create([
            'last_commit_message' => null,
            'url' => $url,
            'name' => 'biotech'
        ]);

        $class = new SyncLastCommitMessage($repository);

        $class->handle();

        $this->assertNotEmpty($repository->fresh()->last_commit_message);

        exec('rm -rf ~/data/biotech');
    }

    protected function preventRunningGitClone(): void
    {
        Event::fake();
    }
}
