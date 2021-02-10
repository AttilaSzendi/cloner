<?php

namespace App\Console\Commands;

use App\Jobs\SyncLastCommitMessage;
use App\Models\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncLastCommitMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:sync-last-commit-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Repository::query()->where('status_id', Repository::CLONED)->chunk(200, function ($repos) {
            foreach ($repos as $repo) {
                SyncLastCommitMessage::dispatch($repo);
            }
        });
    }
}
