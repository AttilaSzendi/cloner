<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepositoryRequest;
use App\Http\Resources\RepositoryResource;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RepositoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $repositories = Repository::query()
            ->when($request->has('name'), function(Builder $query) use ($request) {
                $searchString = $request->input('name');
                $query->where('name', 'LIKE', "%$searchString%");
            })
            ->paginate($request->has('size') ? $request->input('size') : 15);

        return RepositoryResource::collection($repositories);
    }

    public function store(RepositoryRequest $request): RepositoryResource
    {
        $request->merge(['name' => $this->getName($request)]);

        $repository = Repository::query()->create($request->all());

        return new RepositoryResource($repository);
    }

    protected function getName(Request $request): string
    {
        return $request->has('name')
            ? $request->get('name')
            : $this->findOutName($request);
    }

    protected function findOutName(Request $request): string
    {
        $url = $request->input('url');

        $urlSegments = explode('/', $url);

        $lastUrlSegment = end($urlSegments);

        return trim($lastUrlSegment, '.git');
    }
}
