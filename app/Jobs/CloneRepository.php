<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloneRepository implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const ERROR_RESPONSE_CODE = 128;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): void
    {
        $url = $this->repository->url;
        $name = $this->repository->name;

        $output = null;
        $resultCode = null;

        exec("git clone $url ~/data/$name", $output, $resultCode);

        $this->repository->update(['status_id' => $this->getStatusId($resultCode)]);
    }

    protected function getStatusId(int $resultCode): int
    {
        return $resultCode === self::ERROR_RESPONSE_CODE
            ? Repository::INVALID
            : Repository::CLONED;
    }
}
