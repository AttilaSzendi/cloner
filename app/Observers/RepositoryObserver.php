<?php

namespace App\Observers;

use App\Jobs\CloneRepository;
use App\Models\Repository;

class RepositoryObserver
{
    public function created(Repository $repository)
    {
        CloneRepository::dispatch($repository);
    }
}
