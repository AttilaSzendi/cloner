<?php

namespace Tests\Integration;

use App\Jobs\CloneRepository;
use App\Jobs\SyncLastCommitMessage;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SyncLastCommitMessageCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_syncing_job_should_dispatched()
    {
        Queue::fake();

        $count = 2;

        Repository::factory()->count($count)->cloned()->create();

        $this->artisan('repository:sync-last-commit-messages');

        Queue::assertPushed(SyncLastCommitMessage::class, $count);
    }
}
